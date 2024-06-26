<?php
include('includes/config.php');
include('includes/format_rupiah.php');
include('includes/library.php');

$kode = isset($_GET['kode']) ? $_GET['kode'] : ''; // Periksa apakah 'kode' ada dalam query string

if (!empty($kode)) {
    $sql1 = "SELECT booking.*, mobil.*, merek.*, users.* FROM booking,mobil,merek,users WHERE booking.id_mobil=mobil.id_mobil 
            AND merek.id_merek=mobil.id_merek and booking.email=users.email and booking.kode_booking='$kode'";
    $query1 = mysqli_query($koneksidb, $sql1);

    if ($query1 && mysqli_num_rows($query1) > 0) { // Periksa apakah query berhasil dan mengembalikan baris yang valid
		$result = mysqli_fetch_array($query1);
        $harga = isset($result['harga']) ? $result['harga'] : 0;
        $durasi = isset($result['durasi']) ? $result['durasi'] : 0;
        $totalmobil = $durasi * $harga;
        $drivercharges = isset($result['driver']) ? $result['driver'] : 0;
        $totalsewa = $totalmobil + $drivercharges;
        $tglmulai = isset($result['tgl_mulai']) ? strtotime($result['tgl_mulai']) : time();
        $jmlhari = 86400 * 1;
        $tgl = $tglmulai - $jmlhari;
        $tglhasil = date("Y-m-d", $tgl);
    } else {
        echo "Data tidak ditemukan."; // Tampilkan pesan jika data tidak ditemukan
    }
} else {
    echo "Kode sewa tidak valid."; // Tampilkan pesan jika kode sewa tidak tersedia
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="rental mobil">
	<meta name="author" content="universitas pamulang">

	<title>Cetak Detail Sewa</title>

	<link href="assets/images/quick vehicle rent.png" rel="icon" type="images/x-icon">

	<!-- Bootstrap Core CSS -->
	<link href="assets/new/bootstrap.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/new/offline-font.css" rel="stylesheet">
	<link href="assets/new/custom-report.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="assets/new/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- jQuery -->
	<script src="assets/new/jquery.min.js"></script>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<section id="header-kop">
		<div class="container-fluid">
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td rowspan="3" width="16%" class="text-center">
							<img src="assets/images/quick vehicle rent.png" alt="logo-dkm" width="80" />
						</td>
						<td class="text-center"><h3>Quick Vehicle Rent</h3></td>
						<td rowspan="3" width="16%">&nbsp;</td>
					</tr>
					<tr>
						<td class="text-center"><h2>Quick Vehicle Rent</h2></td>
					</tr>
					<tr>
						<td class="text-center">Komp. Permata Biru No.69, RT.06/RW.19, Cinunuk, Kec. Cileunyi, Kab. Bamdung, Jawa Barat </td>
					</tr>
				</tbody>
			</table>
			<hr class="line-top" />
		</div>
	</section>

	<section id="body-of-report">
		<div class="container-fluid">
			<h4 class="text-center">Detail Sewa</h4>
			<br />
			<table class="table table-borderless">
				<tbody>
					<tr>
						<td width="23%">No. Sewa</td>
						<td width="2%">:</td>
						<td><?php echo isset($result['kode_booking']) ? $result['kode_booking'] : ''; ?></td>
					</tr>
					<tr>
						<td>Penyewa</td>
						<td>:</td>
						<td><?php echo isset($result['nama_user']) ? $result['nama_user'] : ''; ?></td>
					</tr>
					<tr>
						<td>Mobil</td>
						<td>:</td>
						<td><?php echo isset($result['nama_merek']) ? $result['nama_merek'] : ''; ?><?php echo isset($result['nama_mobil']) ? ", " . $result['nama_mobil'] : ''; ?></td>
					</tr>
					<tr>
						<td>Tanggal Mulai</td>
						<td>:</td>
						<td><?php echo isset($result['tgl_mulai']) ? IndonesiaTgl($result['tgl_mulai']) : ''; ?></td>
					</tr>
					<tr>
						<td>Tanggal Selesai</td>
						<td>:</td>
						<td><?php echo isset($result['tgl_selesai']) ? IndonesiaTgl($result['tgl_selesai']) : ''; ?></td>
					</tr>
					<tr>
					<td>Durasi</td>
                            <td>:</td>
                            <td><?php echo isset($result['durasi']) ? $result['durasi'] . " Hari" : ''; ?></td>
                        </tr>
                        <tr>
                            <td>Biaya Mobil (<?php echo isset($result['durasi']) ? $result['durasi'] : ''; ?> Hari)</td>
                            <td>:</td>
                            <td><?php echo isset($totalmobil) ? format_rupiah($totalmobil) : ''; ?></td>
                        </tr>
                        <tr>
                            <td>Biaya Driver (<?php echo isset($result['durasi']) ? $result['durasi'] : ''; ?> Hari)</td>
                            <td>:</td>
                            <td><?php echo isset($drivercharges) ? format_rupiah($drivercharges) : ''; ?></td>
                        </tr>
                        <tr>
    <td>Total Biaya Sewa (<?php echo isset($result['durasi']) ? $result['durasi'] : ''; ?> Hari)</td>
    <td>:</td>
    <td><?php echo isset($totalsewa) ? format_rupiah($totalsewa) : ''; ?></td>
</tr>
					<tr>
						<td>Status</td>
						<td>:</td>
						<td><?php echo isset($result['status']) ? $result['status'] : ''; ?></td>
					</tr>
						  <?php if ($result['status'] == "Menunggu Pembayaran") : ?>
							<?php
                            $sqlrek = "SELECT * FROM tblpages WHERE id='5'";
                            $queryrek = mysqli_query($koneksidb, $sqlrek);
                            $resultrek = mysqli_fetch_array($queryrek);
                            ?>
 								<?php if ($resultrek) : ?>
                                <tr>
                                    <td colspan="3">
                                        <b>*Silahkan transfer total biaya sewa ke <?php echo $resultrek['detail']; ?> maksimal tanggal <?php echo isset($tglhasil) ? IndonesiaTgl($tglhasil) : ''; ?>.</b>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3">Data tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
				</tbody>
			</table>
		</div><!-- /.container -->
	</section>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#jumlah').terbilang({
				'style'			: 3, 
				'output_div' 	: "jumlah2",
				'akhiran'		: "Rupiah",
			});

			window.print();
		});
	</script>

	<!-- Bootstrap Core JavaScript -->
	<script src="assets/new/bootstrap.min.js"></script>
	<!-- jTebilang JavaScript -->
	<script src="assets/new/jTerbilang.js"></script>

</body>
</html>