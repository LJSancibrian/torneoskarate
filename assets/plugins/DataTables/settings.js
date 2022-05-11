$.extend($.fn.dataTable.ext.classes, {
    sWrapper: "dataTables_wrapper dt-bootstrap4",
    sFilterInput: "form-control",
    sLengthSelect: "custom-select form-control",
    sProcessing: "dataTables_processing card",
    sPageButton: "paginate_button page-item"
});
$.extend($.fn.dataTable.defaults, {
    info: true,
    paging: true,
    ordering: true,
    searching: true,
    stateSave: false,
    processing: false,
    serverSide: false,
    scrollX: true,
    scrollY: true,
    autoWidth: true,
    order: [0, "desc"],
    pageLength: 25,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'Todos']
    ],
    language: { url: base_url + "assets/plugins/DataTables/languages/spanish.lang.json" },
    dom:
    //"<'row'<'col-md-6'l><'col-md-6 text-md-right'B><'d-none'f>>" +
        "<'d-flex flex-wrap justify-content-between'<''l><'#buttons'B><''f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
});
$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';
$.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-outline-secondary btn-rounded btn-sm';
$.fn.dataTable.Buttons.defaults.buttons = ['excel', 'csv'];
var oldExportAction = function(self, e, dt, button, config) {
    if (button[0].className.indexOf('buttons-excel') >= 0) {
        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
        } else {
            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
        }
    } else if (button[0].className.indexOf('buttons-print') >= 0) {
        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
    }
};
var newExportAction = function(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function(e, s, data) {
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function(e, settings) {
            oldExportAction(self, e, dt, button, config);
            dt.one('preXhr', function(e, s, data) {
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            setTimeout(dt.ajax.reload, 0);
            return false;
        });
    });
    dt.ajax.reload();
};