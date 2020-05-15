/* CLASS AND FOCUS ON CLICK */

$('.toggle').on('click', function() {
  $( '.data-table__account-wrapper.active' ).removeClass('active');
  $(this).closest( '.data-table__account-wrapper' ).addClass('active');
})

$('.asset-wrapper__table .toggle').on('click', function() {
    $('.item.active').removeClass('active');
    $(this).closest( '.item' ).toggleClass('active');
    var selectedId = '.' + $(this).closest( '.item' ).attr('data-asset');
    $('circle.selected').removeClass('selected');
    $('text.active').removeClass('active');
    $('circle' + selectedId).addClass('selected');
    $('text' + selectedId).addClass('active');
})

$('circle.donut-segment').on('click', function() {
    $('circle.donut-segment.selected').removeClass('selected');
    $(this).addClass('selected');
    $('.item.active, text.active').removeClass('active');
    var selectedId = '.' + $(this).attr('id');
    $(selectedId).toggleClass('active');
    $('text' + selectedId).addClass('active');
})

$.fn.toggleText = function(t1, t2){
  if (this.text() == t1) this.text(t2);
  else                   this.text(t1);
  return this;
};

$('.data-toggle').on('click', function() {
    $('.main-content').toggleClass('show-chart');
    $(this).toggleText('View Tables', 'View Charts');
})

$(".add").click(function(e){
  e.preventDefault();
  $("#staffdetails").load("add_staff.php");
});

$(".edit").click(function(e){
  e.preventDefault();
  var staff_id = getParameterByName('id',$(this).attr('href'));
    console.log(staff_id);
  $("#staffdetails").load("edit_staff.php?id="+staff_id);
});

$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('.fund-toggle').on('click', function() {
    $(this).closest('form.fund').toggleClass('active');
})
/*$( document ).ready(function() {
    $(".table").tablesorter();
});*/

function getParameterByName(name, url) {
if (!url) url = window.location.href;
name = name.replace(/[\[\]]/g, "\\$&");
var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
if (!results) return null;
if (!results[2]) return '';
return decodeURIComponent(results[2].replace(/\+/g, " "));
}

/*=== Time Out Function ===*/

var idleTime = 0;
var seconds = 0;
var maxTime = 1430;
var timeOutWrapper = document.getElementById('time-out');
var secondsCounter = document.getElementById('seconds-counter');
var counterChart = document.getElementById('counter-chart');

$(document).ready(function () {

    var idleInterval = setInterval(idleIncrement, 100);
    var timeInterval = setInterval(timeIncrement, 100);

    function resetCounter() {
        idleTime = 0;
        seconds = 0;
        maxTime = 1430;
        secondsCounter.innerText = "";
        counterChart.className = "";
    }

    $(this).mousemove(function (e) {
        resetCounter();
    });

    $(this).keypress(function (e) {
        resetCounter();
    });

    function idleIncrement() {
        idleTime = idleTime + 1;
        if (idleTime > 14300) {
            window.location.reload();
        }
    }

    function timeIncrement() {
        if (idleTime > 10) {
            seconds += 1;
            maxTime -= 1;
            var timeInMins = Math.ceil(maxTime / 60);
            var secsToPercent = Math.ceil((seconds / 60) * 100);
            secondsCounter.innerText = timeInMins + " mins left."
            counterChart.className = "progress-circle progress-" + secsToPercent;
            if (seconds == 60) {
                seconds = 0;
            }
        }
    }
});

function logOut() {
    $.ajax({
        type: "GET",
        url: "logout.php",
        success: function () {
           $('#loggedout').modal({backdrop: 'static', keyboard: false});
        }
    });
}
