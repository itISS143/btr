function generatePdf(approval) {
    // Hide the watermark before generating the PDF
    document.getElementById('watermark').style.display = 'none';
    window.scrollTo(0, 0);

    let tripRoutingSection = document.getElementById('trip-routing-section');
    if (tripRoutingSection) {
        tripRoutingSection.style.pageBreakBefore = 'always';
    }

    const element = document.getElementById('pdf-content');
    const image = document.createElement('img');
    if (approval == "Approved") {
        image.src = 'approved_logo.jpg';
    } else if (approval == "Rejected") {
        image.src = 'rejected logo.webp';
    } else {
        image.src = '';
    }

    element.appendChild(image);

    image.style.width = '710px'; // Set the width of the image
    image.style.opacity = '0.2';
    image.style.position = 'absolute';
    image.style.top = '200px';
    image.style.left = '30px';


    const pdfOptions = {
        margin: 5,
        filename: `Travel_Request.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        display: 'fullpage', // Set the display mode to 'fullpage'
        pagebreak: { before: '.estimated-cost-page' } // Corrected property name
    };
    
    // Use the html2pdf library to save the PDF
    html2pdf()
        .set(pdfOptions) // Set the options
        .from(element)
        .save();
    
        setTimeout(function() {
            window.location.reload();
        }, 1000);
        
}


document.addEventListener("DOMContentLoaded", () => {
    const img = document.createElement('img');
    img.src = 'logo-ISS.png'; // Ganti dengan URL/path gambar yang sesuai
    img.alt = 'Deskripsi gambar';

    // Menambahkan gambar ke dalam elemen dengan id "gambar-container"
    const container = document.getElementById('gambar-container');
    container.appendChild(img);
});