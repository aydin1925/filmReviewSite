// --- SWEETALERT2 OTOMATİK YÜKLEYİCİ ---
// Eğer sayfada SweetAlert yoksa, otomatik olarak CDN'den çeker.
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(script);
}

// Silme işlemi için onay kutusu (SweetAlert2 - show_result tarzında)
function confirmDelete(url) {

    Swal.fire({
        title: 'Dikkat!', 
        text: "Bu işlemi geri alamazsınız. Silmek istediğinize emin misiniz?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#1e3a8a',
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'Vazgeç',
        width: 400,
        background: '#f1f5f9',
        color: '#333',
        customClass: {
            popup: 'border-top-5',
            title: 'fw-bold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Onaylandı, yönlendiriliyor...");
            window.location.href = url;
        } else {
            console.log("İptal edildi.");
        }
    });
    
    return false;
}