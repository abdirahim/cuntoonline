@extends('admin.layouts.default')

@section('content')

<!--- graph js start here-->
{{ HTML::script('js/admin/plugins/flot/plugins/jquery.flot.tooltip.min.js') }}
{{ HTML::script('js/admin/plugins/flot/plugins/jquery.flot.resize.min.js') }}
{{ HTML::script('js/admin/plugins/flot/jquery.flot.min.js') }}
<!-- graph js end here -->

<script type="text/javascript">
	/* For Graph on dashboard */
	var ticks	=	[];
	var data	=	[];
	<?php 
	$num = 0;
	foreach($allUsers as $graphData){ ?>
	data.push([<?php echo $num; ?>, <?php echo $graphData['users']; ?>]);
	var dataset = [{ label: "{{ trans('messages.dashboard.active_users') }}", data: data, color: "#5482FF" }];
	ticks.push([<?php echo $num; ?>, "<?php echo date('M' ,strtotime($graphData['month'])); ?>"]);
	<?php $num ++; } ?>
	$.each(ticks,function(k,v){
		console.log(v);
	});
	
	/* Prepare Options */
	var options = {
		series: {
			bars: {
				show: true
			}
		},
		bars: {
			align: "center",
			barWidth: 0.5
		},
		xaxis: {
			axisLabel: "Months",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 10,
			ticks: ticks
		},
		yaxis: {
			axisLabel: "No. of users",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 3,
			tickFormatter: function (v, axis) {
				return v + "";
			}
		},
		legend: {
			noColumns: 0,
			labelBoxBorderColor: "#000000",
			position: "nw"
		},
		grid: {
			hoverable: true,
			borderWidth: 2,
			backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
		}
	};
	$(document).ready(function () {
		$.plot($("#flot-placeholder"), dataset, options);
		$("#flot-placeholder").UseTooltip();
	});

	function gd(year, month, day) {
		return new Date(year, month, day).getTime();
	}

	var previousPoint = null, previousLabel = null;
	$.fn.UseTooltip = function () {
		$(this).bind("plothover", function (event, pos, item) {
			if (item) {
				if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
					previousPoint = item.dataIndex;
					previousLabel = item.series.label;
					$("#tooltip").remove();

					var x = item.datapoint[0];
					var y = item.datapoint[1];

					var color = item.series.color;

				  	showTooltip(item.pageX,
					item.pageY,
					color,
					"<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
				}
			} else {
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	};
	/* For tooltip */
	function showTooltip(x, y, color, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y - 40,
			left: x - 120,
			border: '2px solid ' + color,
			padding: '3px',
			'font-size': '9px',
			'border-radius': '5px',
			'background-color': '#fff',
			'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
			opacity: 0.9
		}).appendTo("body").fadeIn(200);
	}
</script>

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span><i class="icon-graph"></i> {{ trans("messages.dashboard.status") }}</span>
	</div>
	<div class="mws-panel-body">
		<div id="flot-placeholder" style="height: 322px;"></div>
	</div>
</div>


@stop
