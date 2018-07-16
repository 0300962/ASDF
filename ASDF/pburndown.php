<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:57
 */
include 'header.php';
?>
<script> //Sets the navbar link to show which page you're on
    document.getElementById("performance").className += " active";
</script>
<!-- Google Charts API -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    //Loads Google Charts core functionality
    google.charts.load('current', {'packages':['corechart']});

    //Draws the graph once loaded
    google.charts.setOnLoadCallback(drawGraph);

    <?php //Gets the history of previous Sprints
            $sql = "SELECT * FROM sprints WHERE backlogTotal IS NOT NULL ORDER BY sprintNo ASC;";
            $result = mysqli_query($db, $sql);
            //Builds the list of data
            $graphData = "[['Date', 'PBIs at End', { role: 'annotation' }],";
            while ($row = mysqli_fetch_array($result)) {
                //Breaks the date down into two strings and an integer (months)
                $y = substr($row['endDate'], 0, 5);
                $m = (int)substr($row['endDate'], 5, 2);
                $d = substr($row['endDate'], 7);
                //Decrements the months value to match JavaScripts 0-11 numbering.
                $m--;
                //Rebuilds the date stamp
                $date = $y.$m.$d;
                $date = new DateTime($date);
                //Re-formats the date stamp to be understood by Google Charts, from YYYY-mm-dd to Y,m,d
                $date = date_format($date, 'Y,m,d');
                //Adds the row to the data table for the graph
                $graphData .= "[new Date({$date}), {$row['backlogTotal']},'Sprint {$row['sprintNo']}' ],";
            }
            //Trims the last comma and closes the list
            $graphData = chop($graphData,",");
            $graphData .= "]";
            //Prints the data table for JavaScript to access
            echo "var graphData = {$graphData};";
    ?>
    //Calls the Charts API using the pre-set data
    function drawGraph() {
        //Creates a 'DataTable' from the tabel of data that the charts can then access
        var data = new google.visualization.arrayToDataTable(graphData, false);
        //Sets graph options
        var options = {
            title:'Project Burndown',
            subtitle:'Outstanding Product Backlog Items after each Sprint',
            vAxis: {
                title: 'Product Backlog Items',
                minValue: 0
            },
            width:1000,
            height:600,
            hAxis: {
                format: 'd/M/yy',
                title: 'Date'
            },
            legend: {position: 'none'},
            trendlines: { 0:{color:'#b92f2f',visibleInLegend:false}}, //Linear trendline
            bar: {groupWidth: "25%"}
        };
        //Creates new graph object within container on page
        var burnDown = new google.visualization.ColumnChart(document.getElementById('graph_cont'));
        //Renders graph using the Datatable, with the pre-set options into the Div
        burnDown.draw(data, options);
    }
</script>
<br/>
<div id="disclaimers">
    <div id="graph_cont">
test
    </div>
    <a href="task-board.php">Back</a>
</div>
<?php

