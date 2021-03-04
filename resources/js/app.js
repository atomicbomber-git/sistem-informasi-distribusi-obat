require('./bootstrap');
require('alpinejs')

import Swal from 'sweetalert2'

window.confirmDialog = (overrides) => {
    return Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin melakukan tindakan tersebut?',
        icon: 'warning',
        showCancelButton: true,
    })
}
