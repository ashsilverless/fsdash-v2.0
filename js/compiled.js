"use strict";function getParameterByName(e,t){t||(t=window.location.href),e=e.replace(/[\[\]]/g,"\\$&");var a=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(t);return a?a[2]?decodeURIComponent(a[2].replace(/\+/g," ")):"":null}function logOut(){$.ajax({type:"GET",url:"logout.php",success:function(){$("#loggedout").modal({backdrop:"static",keyboard:!1})}})}$(".toggle").on("click",function(){$(".data-table__account-wrapper.active").removeClass("active"),$(this).closest(".data-table__account-wrapper").addClass("active")}),$(".asset-wrapper__table .toggle").on("click",function(){$(".item.active").removeClass("active"),$(this).closest(".item").toggleClass("active");var e="."+$(this).closest(".item").attr("data-asset");$("circle.selected").removeClass("selected"),$("text.active").removeClass("active"),$("circle"+e).addClass("selected"),$("text"+e).addClass("active")}),$("circle.donut-segment").on("click",function(){$("circle.donut-segment.selected").removeClass("selected"),$(this).addClass("selected"),$(".item.active, text.active").removeClass("active");var e="."+$(this).attr("id");$(e).toggleClass("active"),$("text"+e).addClass("active")}),$.fn.toggleText=function(e,t){return this.text()==e?this.text(t):this.text(e),this},$(".data-toggle").on("click",function(){$(".main-content").toggleClass("show-chart"),$(this).toggleText("View Tables","View Charts")}),$(".add").click(function(e){e.preventDefault(),$("#staffdetails").load("add_staff.php")}),$(".edit").click(function(e){e.preventDefault();var t=getParameterByName("id",$(this).attr("href"));console.log(t),$("#staffdetails").load("edit_staff.php?id="+t)}),$("#confirm-delete").on("show.bs.modal",function(e){$(this).find(".btn-ok").attr("href",$(e.relatedTarget).data("href"))}),$(".fund-toggle").on("click",function(){$(this).closest("form.fund").toggleClass("active")});var idleTime=0,seconds=0,maxTime=1430,timeOutWrapper=document.getElementById("time-out"),secondsCounter=document.getElementById("seconds-counter"),counterChart=document.getElementById("counter-chart");$(document).ready(function(){function t(){seconds=idleTime=0,maxTime=1430,secondsCounter.innerText="",counterChart.className=""}setInterval(function(){idleTime+=1},100),setInterval(function(){if(10<idleTime){seconds+=1,maxTime-=1;var e=Math.ceil(maxTime/60),t=Math.ceil(seconds/60*100);secondsCounter.innerText=e+" mins left.",counterChart.className="progress-circle progress-"+t,60==seconds&&(seconds=0)}},100);$(this).mousemove(function(e){t()}),$(this).keypress(function(e){t()})});