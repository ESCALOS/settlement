<!DOCTYPE html>
<html lang="es">
<head>
	<title>Detalles del Blending - Innova Mining Company</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        html{
            margin-top: 1.4rem;
            margin-left: 4rem;
            margin-right: 4rem;
            font-size: 14px;
        }
        .container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            text-align: center;
        }
        table {
        }
        table td, table th {
            text-align: center;
            padding: 0.25rem !important;
        }

    </style>
</head>
<body>
	<div class="container">
		<div class="">
            <table style="width: 100%" class="text-center">
                <tr>
                    <td width="33.4%">
                        <div class="mb-3 ml-6">
                            <img src="https://i.ibb.co/LJ5dBfF/innova-logo.png" alt="Innova Mining Company" style="max-width: 140px;">
                        </div>
                    </td>
                    <td>
                        <div>
                            <p style="margin-bottom: 0px">Los Pinos, Mza. K Lote 10 - San Juan de Lurigancho</p>
                            <p style="margin-bottom: 0px">Teléfono: 943 358 092</p>
                            <p style="margin-bottom: 0px">info@innovaminingcompany.com</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
		<div class="container">
            <hr>
            <div class="mt-4">
                <h2 class="text-center">
                    Blending
                </h2>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th scope="col">LOTE</th>
                        <th scope="col">CONCENTRADO</th>
                        <th scope="col">TMH</th>
                        <th scope="col">TMNS</th>
                        <th scope="col">SUBTOTAL</th>
                        <th scope="col">IGV</th>
                        <th scope="col">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settlements as $settlement)
                    <tr>
                        <td>{{ $settlement['batch'] }}</td>
                        <td>{{ $settlement['concentrate'] }}</td>
                        <td>{{ number_format($settlement['wmt_to_blending'],3) }}</td>
                        <td>{{ number_format($settlement['dnwmt'],3) }}</td>
                        <td>$ {{ number_format($settlement['amount'] - $settlement['igv'],2) }}</td>
                        <td>$ {{ number_format($settlement['igv'],2) }}</td>
                        <td>$ {{ number_format($settlement['amount'],2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <h3 class="text-center">
                    Promedio de Leyes
                </h3>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th scope="col">Cobre</th>
                        <th scope="col">Plata</th>
                        <th scope="col">Oro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $law['copper'] }}</td>
                        <td>{{ $law['silver'] }}</td>
                        <td>{{ $law['gold'] }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="m-4">
                <h3 class="text-center">
                    Promedio de Penalidades
                </h3>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Elemento</th>
                        <th scope="col">Penalidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>As</td>
                        <td>{{ $penalty['arsenic'] }}</td>
                    </tr>
                    <tr>
                        <td>Sb</td>
                        <td>{{ $penalty['antomony'] }}</td>
                    </tr>
                    <tr>
                        <td>Pb</td>
                        <td>{{ $penalty['lead'] }}</td>
                    </tr>
                    <tr>
                        <td>Zn</td>
                        <td>{{ $penalty['zinc'] }}</td>
                    </tr>
                    <tr>
                        <td>Bi</td>
                        <td>{{ $penalty['bismuth'] }}</td>
                    </tr>
                    <tr>
                        <td>Hg</td>
                        <td>{{ $penalty['mercury'] }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="m-4">
                <h3 class="text-center">
                    Total
                </h3>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">TMH</th>
                        <th scope="col">TMNS</th>
                        <th scope="col">MONTO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">{{ number_format($total['wmt'],3) }}</th>
                        <th scope="row">{{ number_format($total['dnwmt'],3) }}</th>
                        <th scope="row">$ {{ number_format($total['amount'],2) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
	</div>
</body>
</html>
