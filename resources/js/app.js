require('./bootstrap');
require('alpinejs')

window.$ = require("jquery")
window.Cleave = require('cleave.js').default

require('select2')

import Swal from 'sweetalert2'

window.confirmDialog = (overrides) => {
    return Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin melakukan tindakan tersebut?',
        icon: 'warning',
        showCancelButton: true,
    })
}
