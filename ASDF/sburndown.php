<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';

function format_date($date) {
    //Breaks the date down into two strings and an integer (months)
    $y = substr($date, 0, 5);
    $m = (int)substr($date, 5, 2);
    $d = substr($date, 7);
    //Decrements the months value to match JavaScripts 0-11 numbering.
    $m--;
    //Rebuilds the date stamp
    $date = $y.$m.$d;
    $date = new DateTime($date);
    //Re-formats the date stamp to be understood by Google Charts, from YYYY-mm-dd to Y,m,d
    return date_format($date, 'Y,m,d');
}

?>
    <script> //Sets the navbar link and title to show which page you're on
        document.getElementById("performance").className += " active";
        document.title = "ASDF - Sprint Burndown";
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        //Loads Google Charts core functionality
        google.charts.load('current', {'packages':['corechart']});

        //Draws the graph once loaded
        google.charts.setOnLoadCallback(drawSBIs);

        <?php //Gets the total SBIs and dates for the Sprint
        $sql = "SELECT COUNT(sbiNo), startDate, endDate, SUM(effort) 
                FROM sbis, sprints 
                WHERE backlogTotal IS NULL;";
        $result = mysqli_query($db, $sql);
        if (!$result) {
            echo "Error: could not retrieve data from database.";
        }
        $row = mysqli_fetch_array($result);
        $total = $row['COUNT(sbiNo)'];
        $start = format_date($row['startDate']);
        $end = format_date($row['endDate']);
        $effort = $row['SUM(effort)'];

        $sql = "SELECT sbiNo, effort, done FROM sbis WHERE done IS NOT NULL;";
        $result = mysqli_query($db, $sql);
        if (!$result) {
            echo "Error: could not retrieve data from database.";
        }
        //Builds the list of data for the SBI display
        $sbiData = "[['Date', 'SBIs', 'Target'],";
        $sbiData .= "[new Date({$start}), {$total}, {$total}],";
        //Builds the list of data for the Effort display
        $effortData = "[['Date', 'Effort', 'Target'],";
        $effortData .= "[new Date({$start}), {$effort}, {$effort}],";

        $dates = array();
        while ($row = mysqli_fetch_array($result)) {
            //Trims user number from the completion date ('14 2018-07-24'=>'2018-07-24')
            $date = ltrim(substr($row['done'],2));
            //$dates[] = array(format_date($date) => $row['effort']);
            $dates[] = array($date => $row['effort']);
        }

        //Custom comparator function to compare the dates for each array
        function compare($a, $b) {
            $d1 = array_keys($a); //Gets the key value of the first row
            $d1 = $d1[0]; //Extracts to a String
            $d2 = array_keys($b);
            $d2 = $d2[0];
            return strcmp($d1, $d2); //Compares the date strings as strings
        }
        //Puts dates into date-order (SBIs can be completed out of order)
        usort($dates, "compare");

        for ($i = 0; $i < sizeof($dates); $i++) {
            foreach ($dates[$i] as $date => $eff) { //only one row per sub-array
                $total--; //Decrements total SBIs for each one completed
                $effort -= $eff; //Subtracts the effort from the total
            }
            $j = $i + 1;
            //Checks for end-of-array or repeated date

            if (($j == sizeof($dates)) OR (array_keys($dates[$i]) != array_keys($dates[$j]))) {
                $day = array_keys($dates[$i]);
                $day = $day[0];
                $day = format_date($day);

                //Adds a row to the data table for each graph
                $sbiData .= "[new Date({$day}), {$total}, null ],";
                $effortData .= "[new Date({$day}), {$effort}, null ],";
            }
        }
        //Sets the end-date for the Sprint
        $sbiData .= "[new Date($end), null, 0] ]";
        $effortData .= "[new Date($end), null, 0] ]";
        //Prints the data table for JavaScript to access
        echo "var sbiData = {$sbiData};";
        echo "var effortData = {$effortData};";

        ?>
        //Calls the Charts API using the pre-set data
        function drawSBIs() {
            //Creates a 'DataTable' from the table of data that the charts can then access
            var data = new google.visualization.arrayToDataTable(sbiData, false);
            //Sets graph options
            var options = {
                title:'Sprint Burndown',
                subtitle:'Total Outstanding SBIs',
                vAxis: {
                    title: 'Sprint Backlog Items',
                    minValue: 0
                },
                width:900,
                height:600,
                hAxis: {
                    format: 'd/M/yy',
                    title: 'Sprint Date'
                },
                legend: {position: 'none'},
                trendlines: {
                    0: {
                        visibleInLegend:false,
                        title: 'Progress'
                    },
                    1:{
                        color:'#b92f2f',
                        visibleInLegend:false,
                        title: 'Sprint Target'
                    }
                }
            };
            //Creates new graph object within container on page
            var burnDown = new google.visualization.ScatterChart(document.getElementById('graph'));
            //Renders graph using the Datatable, with the pre-set options into the Div
            burnDown.draw(data, options);
        }
        function drawEffort() {
            //Creates a 'DataTable' from the table of data that the charts can then access
            var data = new google.visualization.arrayToDataTable(effortData, false);
            //Sets graph options
            var options = {
                title:'Sprint Effort',
                subtitle:'Total Outstanding SBIs',
                vAxis: {
                    title: 'Remaining Effort Units',
                    minValue: 0
                },
                width:900,
                height:600,
                hAxis: {
                    format: 'd/M/yy',
                    title: 'Sprint Date'
                },
                legend: {position: 'none'},
                trendlines: {
                    0: {
                        visibleInLegend:false,
                        title: 'Progress'
                    },
                    1:{
                        color:'#b92f2f',
                        visibleInLegend:false,
                        title: 'Sprint Target'
                    }
                }
            };
            //Creates new graph object within container on page
            var burnDown = new google.visualization.ScatterChart(document.getElementById('graph'));
            //Renders graph using the Datatable, with the pre-set options into the Div
            burnDown.draw(data, options);
        }
    </script>
    <br/>
    <div id="graph_cont">
        This graph demonstrates the pace of development for the current Sprint. <br/> Click the buttons to view progress
        measured by the number of SBI's left to complete, or alternatively the number of Effort units for the Sprint.<br/>
        <input type="button" onclick="drawSBIs()" value="View SBIs">
        <input type="button" onclick="drawEffort()" value="View Effort units">
        <div id="graph"></div>
        <a href="task-board.php">Back</a>
    </div>
<?php