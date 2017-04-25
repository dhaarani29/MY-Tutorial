var anchor = document.getElementsByClassName('show-tooltip')[0];
var tooltip = document.getElementsByClassName('wk-tooltip')[0];

anchor.addEventListener('mouseover', toggleTooltip, false);
anchor.addEventListener('mouseleave', toggleTooltip, false);
anchor.addEventListener('mousemove', followCursor, false);

function toggleTooltip() {
    var style = window.getComputedStyle(tooltip);
    tooltip.style.visibility = (style.visibility === 'hidden') ? 'visible' : 'hidden';
}

function followCursor(event) {
    tooltip.style.position = 'fixed';
    tooltip.style.top = event.clientY - 50 + 'px';
    tooltip.style.left = event.clientX - 50 + 'px';
}
