using System;
using System.Linq;
using System.Text;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using XRDB4_Extras;

namespace RawItems
{
    public struct Entry
    {
        public int id;
        public int ql;
        public string name;
        public int iconId;
        public int type;
    }

    public class RawItems : XRDB4_Extras.Plugin
    {
        string outPath, aoVer;
        private Entry currentEntry;
        private Dictionary<int, Entry> entries = new Dictionary<int, Entry>();

        XRDB4_Extras.Lookups Lookup = new XRDB4_Extras.Lookups();

        private void storeEntry(Entry entry)
        {
            entries.Add(entry.id, entry);
        }

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

        private void outputSqlFile()
        {
            StreamWriter writer = new StreamWriter(outPath + "\\aodb_items" + aoVer + ".sql", false, System.Text.Encoding.ASCII);
            writer.WriteLine("DROP TABLE IF EXISTS aodb_items;");
            writer.WriteLine("CREATE TABLE aodb_items (aoid INT NOT NULL PRIMARY KEY, ql SMALLINT NOT NULL, name VARCHAR(150) NOT NULL, iconid INT NULL, itemtype SMALLINT NOT NULL);");

            foreach (Entry entry in entries.Values)
            {
                writer.WriteLine(
                    string.Format("INSERT INTO aodb_items (aoid, ql, name, iconid, itemtype) VALUES ({0}, {1}, '{2}', {3}, '{4}');", entry.id, entry.ql, entry.name.Replace("'", "''"), entry.iconId, Lookup.ItemTypes(entry.type))
                );
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
        }

        public void Parse_End(bool Aborted)
        {
            if (Aborted)
            {
                return;
            }

            outputSqlFile();
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
