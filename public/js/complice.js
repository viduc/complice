$(document).ready(function() {
    let urlLangue = 'https://cdn.datatables.net/plug-ins/1.10.22/i18n/';
    urlLangue += locale + '.json'
    $('#datatable').DataTable({
        language: {
            url: urlLangue
        }
    });
} );