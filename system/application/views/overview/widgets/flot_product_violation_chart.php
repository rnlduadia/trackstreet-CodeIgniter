<div class="panel panel-default">
    <div class="panel-heading">
        Product Violations - Last 180 Days
    </div>
    <div class="panel-body">
        <div id="flot-basic-chart">
        </div>
    </div>
</div>

<script type="text/javascript">

function showTooltip(x, y, contents) {
    jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5
    }).appendTo("body").fadeIn(200);
}


    if ($('#flot-basic-chart').length) {
    	
        //var series1 = [[0, 10], [1, 6], [2,3], [3, 8], [4, 5], [5, 13], [6, 8]];
        
        
        var series1 = [
            <?php 
                               
            $i = 0; 
            
            $mod_amount = count($violations) / 20;
            $mod_amount = intval($mod_amount);
            
            $last_month = FALSE;
            
            ?>            
            <?php foreach ($violations as $data_point): ?>
                <?php if (count($violations) > 45): ?>
                    <?php
                    
                    $current_month = date('M', strtotime($data_point['select_date']));
                    
                    ?>
                    <?php if ($current_month != $last_month): ?>
                        [
                            "<?php echo date('M', strtotime($data_point['select_date'])); ?> <?php echo date('j', strtotime($data_point['select_date'])); ?>", 
                            <?php echo $data_point['violation_count']; ?>
                        ],
                    <?php else: ?>
                        [
                             "<!-- <?php echo date('M', strtotime($data_point['select_date'])); ?> <br/><?php echo date('j', strtotime($data_point['select_date'])); ?> -->", 
                             <?php echo $data_point['violation_count']; ?>
                        ],                        
                    <?php endif; ?>   
                <?php else: ?>
                    [
                        "<?php echo date('M', strtotime($data_point['select_date'])); ?><br /><?php echo date('j', strtotime($data_point['select_date'])); ?>", 
                         <?php echo $data_point['violation_count']; ?>
                    ],
                <?php endif; ?>                     
                <?php 
                
                $i++;
                 
                $last_month = $current_month;
                
                ?>
            <?php endforeach; ?>
        ];
        
    
        var plot = $.plot($("#flot-basic-chart"),
            [ { data: series1,
                //label: "Series 1",
                //color: "#8cc152"
                color: "#00a0d1"
            },
            ],
            {
                canvas: false,
                series: {
                    lines: {
                        show: true,
                        fill: true,
                        lineWidth: 1,
                        fillColor: {
                            colors: [ { opacity: 0.5 },
                                { opacity: 0.5 }
                            ]
                        }
                    },
                    points: {
                        show: false
                    },
                    shadowSize: 0
                },
                legend: {
                    position: 'nw'
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    borderColor: '#ddd',
                    borderWidth: 1,
                    labelMargin: 10,
                    backgroundColor: '#fff'
                },
                yaxis: {
                    //min: 0,
                    //max: 15,
                    color: '#eee'
                },
                xaxis: {
                	mode: "categories",
                    color: '#eee',
                    tickSize: 5
                }
            });
    
        var previousPoint = null;
        
        $("#flot-basic-chart").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));
    
            if(item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
    
                    $("#tooltip").remove();
                    
                    var x = item.datapoint[0],
                        y = item.datapoint[1];

                    var data_date = item.series.data[x][0].replace('<br/>', '');
    
                    showTooltip(item.pageX, item.pageY, y + ' violations on ' + data_date);
                }
    
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
    
        });
    
        $("#flot-basic-chart").bind("plotclick", function (event, pos, item) {
            if (item) {
                plot.highlight(item.series, item.datapoint);
            }
        });
    }

</script>