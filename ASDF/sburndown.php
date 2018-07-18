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
        $sql = "SELECT COUNT(sbiNo), startDate, endDate FROM sbis, sprints WHERE backlogTotal IS NULL;";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        $total = $row['COUNT(sbiNo)'];
        $start = format_date($row['startDate']);
        $end = format_date($row['endDate']);

        $sql = "SELECT sbiNo, effort, done FROM sbis WHERE done IS NOT NULL;";
        $result = mysqli_query($db, $sql);
        //Builds the list of data
        $graphData = "[['Date', 'SBIs', 'Target'],";
        $graphData .= "[new Date({$start}), null, {$total}],";

        $dates = array();
        while ($row = mysqli_fetch_array($result)) {
            //Trims user number from the completion date ('14 2018-07-24'=>'2018-07-24')
            $date = ltrim(substr($row['done'],2));
            $dates[] = format_date($date);
        }
        //Puts dates into date-order (SBIs can be completed out of order)
        sort($dates);
        $previous = 0;

        for ($i = 0; $i < sizeof($dates); $i++) {
            //Decrements total SBIs for each one completed
            $total--;
            $j = $i + 1;
            if (($j == sizeof($dates)) OR ($dates[$i] != $dates[$j])) {
                //Adds the row to the data table for the graph
                $graphData .= "[new Date({$dates[$i]}), {$total}, null ],";
            }
        }
        //Sets the end-date for the Sprint
        $graphData .= "[new Date($end), null, 0] ]";
        //Prints the data table for JavaScript to access
        echo "var graphData = {$graphData};";
        ?>
        //Calls the Charts API using the pre-set data
        function drawSBIs() {

            //Creates a 'DataTable' from the tabel of data that the charts can then access
            var data = new google.visualization.arrayToDataTable(graphData, false);
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
                    title: 'Date'
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
        This graph demonstrates the pace of development for the current Sprint.
        <div id="graph"></div>
        <a href="task-board.php">Back</a>
    </div>
<?php