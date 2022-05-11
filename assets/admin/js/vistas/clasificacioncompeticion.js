var titledoc = $('.page-title').text();
$('#clasificaicon').dataTable({
    "ordering": false,
    "pageLength": 50,
    buttons: [
        {name: 'excel', extend: 'excel', filename: titledoc, sheetName: 'Results', title: titledoc},
        {name: 'csv',   extend: 'csv', filename: titledoc, title: titledoc}
    ],
});