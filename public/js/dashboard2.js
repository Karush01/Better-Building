function createChartist(tasksstatus) {
    "use strict";
    // ==============================================================
    // Website Visitor
    // ==============================================================

    console.log(tasksstatus);
    var chart = new Chartist.Line('.website-visitor', {
        labels: ['ינואר', 'פברואר', 'מרץ', 'אפריל', 'מאי', 'יוני', 'יולי', 'אוגוסט', 'ספטמבר', 'אוקטובר', 'נובמבר', 'דצמבר'],
        series: [
            tasksstatus['done']
            , tasksstatus['open']
            , tasksstatus['late']
        ],
    }, {
        low: 0,
        high: 40,
        showArea: true,
        fullWidth: true,
        plugins: [
            Chartist.plugins.tooltip()
        ],
        axisY: {
            onlyInteger: true
            , scaleMinSpace: 40
            , offset: 20
            , labelInterpolationFnc: function (value) {
                return (value);
            }
        },
    });
    // Offset x1 a tiny amount so that the straight stroke gets a bounding box
    // Straight lines don't get a bounding box
    // Last remark on -> http://www.w3.org/TR/SVG11/coords.html#ObjectBoundingBox
    chart.on('draw', function (ctx) {
        if (ctx.type === 'area') {
            ctx.element.attr({
                x1: ctx.x1 + 0.001
            });
        }
        if (ctx.type === 'label' && ctx.axis.units.pos === 'x') {
            ctx.element._node.childNodes[0].style.direction = 'ltr';
        }
    });

    // Create the gradient definition on created event (always after chart re-render)
    chart.on('created', function (ctx) {
        var defs = ctx.svg.elem('defs');
        defs.elem('linearGradient', {
            id: 'gradient',
            x1: 0,
            y1: 1,
            x2: 0,
            y2: 0,
            x3: 0,
            y3: 0
        }).elem('stop', {
            offset: 0,
            'stop-color': 'rgba(255, 255, 255, 1)'
        }).parent().elem('stop', {
            offset: 1,
            'stop-color': 'rgba(38, 198, 218, 1)'
        });
    });
};

function createUsersChartist(usersChaar) {
    "use strict";
    // ==============================================================
    // Visitor
    // ==============================================================

    var chart = c3.generate({
        bindto: '#visitor',
        data: {
            columns: [
                ['מנהלים', usersChaar['director']],
                ['חברות ניהול', usersChaar['management_company']],
                ['דיירים', usersChaar['tenant']],
            ],

            type: 'donut',
            onclick: function (d, i) {
                console.log("onclick", d, i);
            },
            onmouseover: function (d, i) {
                console.log("onmouseover", d, i);
            },
            onmouseout: function (d, i) {
                console.log("onmouseout", d, i);
            }
        },
        donut: {
            label: {
                show: false
            },
            title: "משתמשים",
            width: 20,

        },

        legend: {
            hide: true
            //or hide: 'data1'
            //or hide: ['data1', 'data2']
        },
        color: {
            pattern: ['#eceff1', '#745af2', '#26c6da', '#1e88e5']
        }
    });
};

function getDoneTasksByMonth() {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{!! csrf_token() !!}'
        }
    });
    $.ajax({
        url: "{{ url('/home/gettasksbymonthyear') }}",
        method: 'post',
        success: function (result) {
            if (result['success']) {
                alert(result);
            }
        }
    });
};
