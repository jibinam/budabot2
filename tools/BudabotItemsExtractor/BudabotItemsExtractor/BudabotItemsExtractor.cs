//  <Licence>
//  BudabotItemsExtractor - XRDB4 Plugin for Budabot Items Database
//  Copyright © 2011 Tyrence (RK2)
//  Licensed under the MIT license: http://www.opensource.org/licenses/MIT
//  </Licence>
using System;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using XRDB4_Extras;

namespace BudabotItemsExtractor
{
    public struct Entry
    {
        public int id;
        public int ql;
        public string name;
        public int iconId;
        public int type;
        public string hash;
    }

    public class BudabotItemsExtractor : XRDB4_Extras.Plugin
    {
        string outPath, aoVer;
        private Entry currentEntry;
        private Dictionary<int, Entry> entries;
        private StreamWriter logWriter;

        XRDB4_Extras.Lookups Lookup = new XRDB4_Extras.Lookups();

        private int findIconId(XRDB4_Extras.Plugin.ItemNanoKeyVal[] Attributes)
        {
            foreach (XRDB4_Extras.Plugin.ItemNanoKeyVal KVP in Attributes)
            {
                if (KVP.AttrKey == 79)
                {
                    return KVP.AttrVal;
                }
            }
            return 0;
        }

        private void storeEntry(Entry entry)
        {
            entries.Add(entry.id, entry);
        }

        private void logMessage(String message)
        {
            logWriter.WriteLine(message);
        }

        private void processNameSeparations(SQLite3 db)
        {
            db.StartTransaction();
            StreamReader reader = new StreamReader(@"Config\BudabotItemsExtractor\nameseperation_list.txt");
            while (!reader.EndOfStream)
            {
                string line = reader.ReadLine().Trim();
                if (line == "")
                {
                    continue;
                }
                string[] parms = line.Split(',');
                List<Hashtable> commonNames = db.Query(string.Format(
                    "SELECT DISTINCT replace(replace(name, '{0}', ''), '{1}', '') common_name " +
                    "FROM entries WHERE (name LIKE '{2}' OR name LIKE '{3}') " +
                    "AND itemtype = '{4}'",
                    parms[1].Replace("%", "").Replace("'", "''"), parms[0].Replace("%", "").Replace("'", "''"),
                    parms[1].Replace("'", "''"), parms[0].Replace("'", "''"), parms[2]
                ));

                foreach (Hashtable ht in commonNames)
                {
                    List<Hashtable> entries = db.Query(string.Format(
                        "SELECT *, replace(replace(name, '{0}', ''), '{1}', '') common_name " +
                        "FROM entries WHERE (name LIKE '{2}' OR name LIKE '{3}') " +
                        "AND common_name = '{4}' AND itemtype = '{5}' " +
                        "ORDER BY ql ASC",
                        parms[1].Replace("%", "").Replace("'", "''"), parms[0].Replace("%", "").Replace("'", "''"),
                        parms[1].Replace("'", "''"), parms[0].Replace("'", "''"),
                        ht["common_name"].ToString().Replace("'", "''"), parms[2]
                    ));

                    if (entries.Count > 0)
                    {
                        matchEntries(db, entries);
                    }
                }
            }

            // reset file read pointer to start of file
            reader.BaseStream.Position = 0;
            reader.DiscardBufferedData();

            // delete name separation entries so they are not processed again
            while (!reader.EndOfStream)
            {
                string line = reader.ReadLine().Trim();
                if (line == "")
                {
                    continue;
                }
                string[] parms = line.Split(',');
                db.NonQuery(string.Format(
                    "DELETE FROM entries WHERE (name LIKE '{0}' OR name LIKE '{1}') AND itemtype = '{2}'",
                    parms[0].Replace("'", "''"), parms[1].Replace("'", "''"), parms[2]
                ));
            }

            reader.Close();
            db.EndTransaction();
        }

        private void processDeleteList(SQLite3 db)
        {
            db.StartTransaction();
            StreamReader reader = new StreamReader(@"Config\BudabotItemsExtractor\delete_list.txt");
            while (!reader.EndOfStream)
            {
                string line = reader.ReadLine().Trim();
                if (line == "")
                {
                    continue;
                }
                db.Query(string.Format("DELETE FROM entries WHERE {0}", line));
            }
            reader.Close();
            db.EndTransaction();
        }

        private void processStaticList(SQLite3 db)
        {
            db.StartTransaction();
            StreamReader reader = new StreamReader(@"Config\BudabotItemsExtractor\static_list.txt");
            while (!reader.EndOfStream)
            {
                string line = reader.ReadLine().Trim();
                if (line == "")
                {
                    continue;
                }
                string[] parms = line.Split(',');
                logMessage("Processing static entry: '" + parms[0].ToString() + "', '" + parms[1].ToString() + "', '" + parms[2].ToString() + "', '" + parms[3].ToString() + "'");
                Hashtable low = db.QuerySingle(string.Format(
                    "SELECT * FROM entries WHERE aoid = {0} AND ql = {1}",
                    parms[0].ToString(), parms[2].ToString()
                ));

                if (low != null)
                {
                    Hashtable high = new Hashtable();
                    high["aoid"] = parms[1].ToString();
                    high["ql"] = parms[3].ToString();

                    addItem(db, low, high);
                }
                else
                {
                    logMessage("ERROR-Could not find item id '" + parms[0].ToString() + "' at ql '" + parms[2].ToString() + "'");
                }
            }

            // reset file read pointer to start of file
            reader.BaseStream.Position = 0;
            reader.DiscardBufferedData();

            // delete name separation entries so they are not processed again
            while (!reader.EndOfStream)
            {
                string line = reader.ReadLine().Trim();
                if (line == "")
                {
                    continue;
                }
                string[] parms = line.Split(',');
                db.NonQuery(string.Format(
                    "DELETE FROM entries WHERE aoid = {0} AND ql = {1}",
                    parms[0].ToString(), parms[2].ToString()
                ));
            }

            reader.Close();
            db.EndTransaction();
        }

        private void processRemaingingEntries(SQLite3 db)
        {
            db.StartTransaction();
            List<Hashtable> distinctNames = db.Query("SELECT DISTINCT name, itemtype, icon FROM entries");

            foreach (Hashtable ht in distinctNames)
            {
                List<Hashtable> entries = db.Query(string.Format(
                    "SELECT * FROM entries WHERE name = '{0}' AND itemtype = '{1}' AND icon = '{2}' ORDER BY ql ASC",
                    db.Escape(ht["name"].ToString()), ht["itemtype"].ToString(), ht["icon"].ToString()
                ));

                Boolean sequential = true;
                for (int i = 0; i < entries.Count - 1; i++)
                {
                    if (Convert.ToInt32(entries[i]["ql"]) >= Convert.ToInt32(entries[i + 1]["ql"]))
                    {
                        sequential = false;
                        break;
                    }
                }

                if (sequential)
                {
                    logMessage("Sequential ql handling for: '" + ht["name"] + "'");
                    matchEntries(db, entries);
                }
                else
                {
                    logMessage("AOID handling for: '" + ht["name"] + "'");
                    List<Hashtable> entries2 = db.Query(string.Format(
                        "SELECT * FROM entries WHERE name = '{0}' AND itemtype = '{1}' AND icon = '{2}' ORDER BY aoid ASC",
                        db.Escape(ht["name"].ToString()), ht["itemtype"].ToString(), ht["icon"].ToString()
                    ));

                    List<Hashtable> tempEntries = new List<Hashtable>();
                    int currentQl = 0;
                    foreach (Hashtable ht2 in entries2)
                    {
                        if (currentQl >= Convert.ToInt32(ht2["ql"]))
                        {
                            logMessage("Processing temp entries");
                            matchEntries(db, tempEntries);
                            tempEntries = new List<Hashtable>();
                        }

                        logMessage("Adding to temp entries: '" + ht2["name"] + "' '" + ht2["aoid"] + "' '" + ht2["ql"] + "'");
                        currentQl = Convert.ToInt32(ht2["ql"]);
                        tempEntries.Add(ht2);
                    }
                    logMessage("Processing temp entries");
                    matchEntries(db, tempEntries);
                }
            }
            db.EndTransaction();
        }

        private void matchEntries(SQLite3 db, List<Hashtable> entries)
        {
            if (entries == null || entries.Count == 0)
            {
                return;
            }

            logMessage("Matching for: '" + entries[0]["name"] + "' count: " + entries.Count);
            // if there is only one item, match it to itself
            if (entries.Count == 1)
            {
                logMessage("One entry for: '" + entries[0]["name"] + "'");
                addItem(db, entries[0], entries[0]);
                return;
            }

            List<Hashtable> tempEntries = new List<Hashtable>();
            tempEntries.Add(entries[0]);
            int i = 1;
            for (; i < entries.Count - 1; i++)
            {
                if (Convert.ToInt32(entries[i]["ql"]) == Convert.ToInt32(entries[i + 1]["ql"]) - 1)
                {
                    logMessage("QLs already split for: '" + entries[i]["ql"] + "' and '" + entries[i + 1]["ql"] + "'");
                    tempEntries.Add(entries[i]);
                    tempEntries.Add(entries[i + 1]);
                    i++;
                }
                else
                {
                    logMessage("Splitting QLs for: '" + entries[i]["ql"] + "'");
                    Hashtable ht = new Hashtable();
                    ht["aoid"] = entries[i]["aoid"];
                    ht["ql"] = Convert.ToInt32(entries[i]["ql"]) - 1;
                    ht["name"] = entries[i]["name"];
                    ht["icon"] = entries[i]["icon"];
                    ht["itemtype"] = entries[i]["itemtype"];
                    tempEntries.Add(ht);
                    tempEntries.Add(entries[i]);
                }
            }
            if (i < entries.Count)
            {
                tempEntries.Add(entries[entries.Count - 1]);
            }

            int j = 0;
            for (; j < tempEntries.Count - 1; j += 2)
            {
                if (Convert.ToInt32(tempEntries[j]["ql"]) == Convert.ToInt32(tempEntries[j + 1]["ql"]) - 1)
                {
                    addItem(db, tempEntries[j], tempEntries[j]);
                    addItem(db, tempEntries[j + 1], tempEntries[j + 1]);
                }
                else
                {
                    addItem(db, tempEntries[j], tempEntries[j + 1]);
                }
            }
            if (j < tempEntries.Count)
            {
                addItem(db, tempEntries[tempEntries.Count - 1], tempEntries[tempEntries.Count - 1]);
            }
        }

        private void addItem(SQLite3 db, Hashtable low, Hashtable high)
        {
            String sql = string.Format(
                "INSERT INTO aodb (lowid, highid, lowql, highql, name, icon) " +
                "VALUES ({0}, {1}, {2}, {3}, '{4}', {5})",
                low["aoid"].ToString(), high["aoid"].ToString(), low["ql"].ToString(), high["ql"].ToString(),
                db.Escape(low["name"].ToString().Trim()), low["icon"].ToString()
            );
            db.NonQuery(sql);
            logMessage(sql);
        }

        private void writeEntriesToDb(SQLite3 db, Dictionary<int, Entry> entries)
        {
            db.StartTransaction();
            foreach (Entry entry in entries.Values)
            {
                db.NonQuery(string.Format("INSERT INTO entries (aoid, ql, name, icon, itemtype, hash) VALUES ({0}, {1}, '{2}', {3}, '{4}', '{5}')",
                    entry.id, entry.ql, db.Escape(entry.name), entry.iconId, Lookup.ItemTypes(entry.type), db.Escape(entry.hash)));
            }
            db.EndTransaction();
        }

        private void outputSqlFile(SQLite3 db)
        {
            StreamWriter writer = new StreamWriter(outPath + "\\aodb" + aoVer + ".sql", false, System.Text.Encoding.ASCII);
            writer.WriteLine("DROP TABLE IF EXISTS aodb;");
            writer.WriteLine("CREATE TABLE aodb (lowid INT, highid INT, lowql INT, highql INT, name VARCHAR(150), icon INT);");

            List<Hashtable> results = db.Query("SELECT * FROM aodb");

            foreach (System.Collections.Hashtable HT in results)
            {
                writer.WriteLine(string.Format(
                    "INSERT INTO aodb VALUES ({0}, {1}, {2}, {3}, '{4}', {5});",
                    HT["lowid"].ToString(), HT["highid"].ToString(), HT["lowql"].ToString(), HT["highql"].ToString(),
                    db.Escape(HT["name"].ToString()), HT["icon"].ToString()
                ));
            }
            writer.Close();
        }

        #region Plugin Members

        public event XRDB4_Extras.Plugin.AbortEventHandler Abort;
        public event XRDB4_Extras.Plugin.ChangePriorityEventHandler ChangePriority;

        public XRDB4_Extras.ExtractRecordDictionary.ExtractRecord[] ExtractInfo()
        {
            return new XRDB4_Extras.ExtractRecordDictionary.ExtractRecord[] { new XRDB4_Extras.ExtractRecordDictionary().Items };
        }

        public void Parse_Begin(string OutputPath, string AOVersion, bool SkippedCompare, string CommandLine)
        {
            if (SkippedCompare == false)
            {
                Abort("Please skip comparison checks and perform a full parse in order to use this plugin correctly.");
            }

            outPath = OutputPath;
            aoVer = AOVersion;

            ChangePriority(System.Threading.ThreadPriority.Normal);

            entries = new Dictionary<int, Entry>();

            if (logWriter != null)
            {
                logWriter.Close();
            }
            logWriter = new StreamWriter(outPath + "\\log.txt", false, System.Text.Encoding.ASCII);
        }

        public void Parse_End(bool Aborted)
        {
            if (Aborted)
            {
                return;
            }

            SQLite3 db = new XRDB4_Extras.SQLite3();
            //db.Open(outPath + @"\BudabotItemsExtractor.db3", true, false);  // use file db
            db.Open(":memory:", true, false);  // use memory db
            db.NonQuery("DROP TABLE IF EXISTS entries");
            db.NonQuery("DROP TABLE IF EXISTS aodb");
            db.NonQuery("CREATE TABLE entries (aoid INT, ql INT, name TEXT, icon INT, itemtype TEXT, hash TEXT)");
            db.NonQuery("CREATE TABLE aodb (lowid INT, highid INT, lowql INT, highql INT, name VARCHAR(150), icon INT)");

            writeEntriesToDb(db, entries);
            processStaticList(db);
            processDeleteList(db);
            processNameSeparations(db);
            processRemaingingEntries(db);
            outputSqlFile(db);

            db.Close(false);
            logWriter.Close();
            logWriter = null;
        }

        public bool ItemNano_Begin(int aoid, bool IsNano, XRDB4_Extras.Plugin.ChangeStates ChangeState)
        {
            if (IsNano)
            {
                return false;
            }
            currentEntry = new Entry();
            currentEntry.id = aoid;

            return true;
        }

        public void ItemNano(XRDB4_Extras.Plugin.ItemNanoInfo Info, XRDB4_Extras.Plugin.ItemNanoKeyVal[] Attributes)
        {
            currentEntry.name = Info.Name;
            currentEntry.ql = Info.QL;
            currentEntry.type = Info.Type;
            currentEntry.iconId = findIconId(Attributes);
            //currentEntry.hash = Info.Description;
        }

        public void ItemNano_End()
        {
            storeEntry(currentEntry);
        }

        public void ItemNanoAction(int ActionNum, XRDB4_Extras.Plugin.ItemNanoRequirement[] Requirements)
        {

        }


        public void ItemNanoAttackAndDefense(XRDB4_Extras.Plugin.ItemNanoKeyVal[] Attack, XRDB4_Extras.Plugin.ItemNanoKeyVal[] Defense)
        {

        }

        public void ItemNanoEventAndFunctions(int EventNum, XRDB4_Extras.Plugin.ItemNanoFunction[] Functions)
        {

        }

        public void ItemNanoAnimSets(int ActionNum, int[] AnimData)
        {
            //throw new NotImplementedException();
        }
        public void ItemNanoSoundSets(int ActionNum, int[] AnimData)
        {
            //throw new NotImplementedException();
        }
        public bool OtherData_Begin(int AOID, int RecordType, XRDB4_Extras.Plugin.ChangeStates ChangeState)
        {
            return false;
        }
        public void OtherData(byte[] BinaryData)
        {
            //throw new NotImplementedException();
        }
        public void OtherData_End()
        {
            //throw new NotImplementedException();
        }
        #endregion
    }
}
