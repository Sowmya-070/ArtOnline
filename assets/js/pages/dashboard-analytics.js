function floatchart(){new ApexCharts(document.querySelector("#revenue-chart"),{chart:{height:305,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{horizontal:!1,columnWidth:"50%"}},dataLabels:{enabled:!1},colors:["#c7d9ff","#7267EF"],stroke:{show:!0,width:2,colors:["transparent"]},series:[{name:"Brazil",data:[44,55,57,56,61,58,63]},{name:"New york",data:[76,85,101,98,87,105,91]}],xaxis:{categories:["Feb","Mar","Apr","May","Jun","Jul","Aug"]},fill:{opacity:1},tooltip:{y:{formatter:function(e){return"$ "+e+" thousands"}}}}).render(),new ApexCharts(document.querySelector("#customer-chart"),{chart:{height:150,type:"donut"},dataLabels:{enabled:!1},plotOptions:{pie:{donut:{size:"75%"}}},labels:["New","Return"],series:[39,15],legend:{show:!1},grid:{padding:{top:20,right:0,bottom:0,left:0}},colors:["#7267EF","#7267EF"],fill:{opacity:[1,.3]},tooltip:{theme:"dark"},stroke:{width:0}}).render(),new ApexCharts(document.querySelector("#customer-chart1"),{chart:{height:150,type:"donut"},dataLabels:{enabled:!1},plotOptions:{pie:{donut:{size:"75%"}}},labels:["New","Return"],series:[39,8],legend:{show:!1},grid:{padding:{top:20,right:0,bottom:0,left:0}},colors:["#fff","#fff"],fill:{opacity:[1,.3]},tooltip:{fillSeriesColor:!1,theme:"dark"},stroke:{width:0}}).render()}document.addEventListener("DOMContentLoaded",function(){setTimeout(function(){floatchart()},100),new SimpleBar(document.querySelector(".product-scroll")),peity.defaults.donut={delimiter:null,fill:["#ff9900","#fff4dd","#ffd592"],height:null,innerRadius:null,radius:8,width:null},document.querySelectorAll(".donut").forEach(e=>peity(e,"donut"))});