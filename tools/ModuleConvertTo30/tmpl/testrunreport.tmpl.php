<html>
<body>
<h2>Summary</h2>
<p>Passed reports: <?= $successReports ?></p>
<p>Failed reports: <?= $failedReports ?></p>
<p>Passing percentage: <?= intval(floatval($successReports) / floatval($successReports + $failedReports) * 100.0) ?>%</p>
<h2>Results</h2>
<? foreach($reports as $report): ?>
<b><?= $report->name ?></b>
<? if ($report->success): ?>
<p><font color="green">Success</font></p>
<? else: ?>
<p><font color="red">FAIL!</font></p>
<p><?= $report->errors ?></p>
<? endif ?>
<? endforeach ?>
</body>
</html>