function approveRequest() {

    const confirmApprove = confirm(
        "Apakah Anda yakin ingin menyetujui permohonan izin ini?"
    );


    if (confirmApprove) {

        alert(
            "Permohonan izin berhasil disetujui!"
        );

        sponse => response.json())
        .then(data => {
            console.log(data);
        });

    }

}


function rejectRequest() {

    const confirmReject = confirm(
        "Apakah Anda yakin ingin menolak permohonan izin ini?"
    );


    if (confirmReject) {

        alert(
            "Permohonan izin berhasil ditolak!"
        );

    }

}


function scrollToBottom() {

    window.scrollTo({

        top: document.body.scrollHeight,

        behavior: "smooth"

    });

}