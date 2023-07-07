<!DOCTYPE html>
<html lang="es">
<head>
	<title>Liquidación {{ $settlement->batch }} - Innova Mining Company</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        html{
            margin-top: 1.4rem;
            margin-left: 4rem;
            margin-right: 4rem;
            font-size: 11px;
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
            <form>
                <div>
                    <table style="width: 100%" align="center">
                        <tr>
                            <td width="49%">
                                <label class="mb-0">Ticket:</label>
                                <input type="text" class="form-control" readonly value="{{ $settlement->batch }}">
                            </td>
                            <td></td>
                            <td width="1%"></td>
                            <td></td>
                            <td width="49%">
                                <label class="mb-0">Nro. Documento:</label>
                                <input type="text" class="form-control" readonly value="{{ $settlement->Order->Client->document_number }}">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <label class="mb-0">Nombre o Razón Social Cliente:</label>
                                <input type="text" class="form-control" readonly value="{{ $settlement->Order->Client->name }}">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="concentrado" class="mb-0">Concentrado:</label>
                                <input type="text" class="form-control" readonly value="{{ $settlement->Order->Concentrate->concentrate }}">
                            </td>
                            <td></td>
                            <td width="10px"></td>
                            <td></td>
                            <td>
                                <table style="width: 100%" class="text-center">
                                    <tr>
                                        <td style="width: 45%">
                                            <label for="tmh" class="mb-0">TMH:</label>
                                            <input type="text" class="form-control" readonly value="{{ floatval($settlement->Order->wmt) }}">
                                        </td>
                                        <td style="width: 5%"></td>
                                        <td style="width: 45%">
                                            <label for="tmh" class="mb-0">TMNS:</label>
                                            <input type="text" class="form-control" readonly value="{{ floatval($settlement->Law->tmns) }}">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <label for="procedencia" class="mb-0">Procedencia:</label>
                                <input type="text" class="form-control" readonly value="{{ $settlement->Order->origin }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
            <div class="mt-4">
                <h5 class="text-center">
                    Precio Internacional
                </h5>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th scope="col">Cobre Internacional</th>
                        <th scope="col">Plata Internacional</th>
                        <th scope="col">Oro Internacional</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ floatval($settlement->InternationalPayment->copper) }}</td>
                        <td>{{ floatval($settlement->InternationalPayment->silver) }}</td>
                        <td>{{ floatval($settlement->InternationalPayment->gold) }}</td>
                    </tr>
                </tbody>
            </table>
            <div>
                <h5 class="text-center">
                    Ensayes
                </h5>
            </div>
            <table class="table table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th scope="col">Ley Cobre(%)</th>
                        <th scope="col">Humedad H2O(%)</th>
                        <th scope="col">Merma</th>
                        <th scope="col">Ley Plata(Oz)</th>
                        <th scope="col">Ley Oro(Oz)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ floatval($settlement->Law->copper) }}%</td>
                        <td>{{ floatval($settlement->Law->humidity) }}%</td>
                        <td>{{ floatval($settlement->Law->decrease) }}%</td>
                        <td>{{ floatval($settlement->Law->silver) }}</td>
                        <td>{{ floatval($settlement->Law->gold) }}</td>
                    </tr>
                </tbody>
            </table>
            <div>
                <table class="table table-bordered" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Metal</th>
                            <th scope="col">Leyes</th>
                            <th scope="col">%Pagable</th>
                            <th scope="col">Deducción</th>
                            <th scope="col">Precio</th>
                            <th scope="col">US$/TM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cu %</td>
                            <td>{{ floatval($settlement->Law->copper) }}%</td>
                            <td>{{ floatval($settlement->PercentagePayable->copper) }}%</td>
                            <td>{{ floatval($settlement->Deduction->copper) }}%</td>
                            <td>$ {{ number_format($settlement->PayableTotal->unit_price_copper,3) }}</td>
                            <td>$ {{ number_format($settlement->PayableTotal->total_price_copper,3) }}</td>
                        </tr>
                        <tr>
                            <td>Ag Oz/TC</td>
                            <td>{{ floor($settlement->Law->silver*$settlement->Law->silver_factor*1000)/1000 }}</td>
                            <td>{{ floatval($settlement->PercentagePayable->silver) }}%</td>
                            <td>{{ floatval($settlement->Deduction->silver) }}</td>
                            <td>$ {{ number_format($settlement->PayableTotal->unit_price_silver,3) }}</td>
                            <td>$ {{ number_format($settlement->PayableTotal->total_price_silver,3) }}</td>
                        </tr>
                        <tr>
                            <td>Au Oz/TC</td>
                            <td>{{ floor($settlement->Law->gold*$settlement->Law->gold_factor*1000)/1000 }}</td>
                            <td>{{ floatval($settlement->PercentagePayable->gold) }}%</td>
                            <td>{{ floatval($settlement->Deduction->gold) }}</td>
                            <td>$ {{ number_format($settlement->PayableTotal->unit_price_gold,3) }}</td>
                            <td>$ {{ number_format($settlement->PayableTotal->total_price_gold,3) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td scope="row">
                                <strong>Total pagable / TM</strong>
                            </td>
                            <td scope="row">$ {{ number_format($settlement->SettlementTotal->payable_total,3) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-bordered" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Concepto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">US$/TM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Refinación de Cu</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->unit_price_copper,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->total_price_copper,3) }}</td>
                        </tr>
                        <tr>
                            <td>Refinación de Ag</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->unit_price_silver,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->total_price_silver,3) }}</td>
                        </tr>
                        <tr>
                            <td>Refinación de Au</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->unit_price_gold,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->total_price_gold,3) }}</td>
                        </tr>
                        <tr>
                            <td>Maquila</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->maquila,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->maquila,3) }}</td>
                        </tr>
                        <tr>
                            <td>Análisis</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->analysis,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->analysis,3) }}</td>
                        </tr>
                        <tr>
                            <td>Estibadores</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->stevedore,3) }}</td>
                            <td>$ {{ number_format($settlement->DeductionTotal->stevedore,3) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td scope="row">
                                <strong>Total Deducciones</strong>
                            </td>
                            <td scope="row">$ {{ number_format($settlement->SettlementTotal->deduction_total,3) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-bordered" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Elemento</th>
                            <th scope="col">Penalidad</th>
                            <th scope="col">Máximo Permitido</th>
                            <th scope="col">US$/TM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>As</td>
                            <td>$ {{ number_format($settlement->Penalty->arsenic,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->arsenic,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_arsenic,3) }}</td>
                        </tr>
                        <tr>
                            <td>Sb</td>
                            <td>$ {{ number_format($settlement->Penalty->antomony,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->antomony,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_antomony,3) }}</td>
                        </tr>
                        <tr>
                            <td>Bi</td>
                            <td>$ {{ number_format($settlement->Penalty->bismuth,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->bismuth,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_bismuth,3) }}</td>
                        </tr>
                        <tr>
                            <td>Pb</td>
                            <td>$ {{ number_format($settlement->Penalty->lead,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->lead,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_lead,3) }}</td>
                        </tr>
                        <tr>
                            <td>Zn</td>
                            <td>$ {{ number_format($settlement->Penalty->zinc,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->zinc,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_zinc,3) }}</td>
                        </tr>
                        <tr>
                            <td>Hg</td>
                            <td>$ {{ number_format($settlement->Penalty->mercury,3) }}</td>
                            <td>$ {{ number_format($settlement->AllowedAmount->mercury,3) }}</td>
                            <td>$ {{ number_format($settlement->PenaltyTotal->total_mercury,3) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td scope="row">
                                <strong>Total Penalidades</strong>
                            </td>
                            <td scope="row">$ {{ number_format($settlement->SettlementTotal->penalty_total,3) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-bordered" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Total US$/TM</th>
                            <th scope="col">Valor por Lote US$</th>
                            <th scope="col">IGV</th>
                            <th scope="col">Detracción</th>
                            <th scope="col">Total de liquidación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">$ {{ number_format($settlement->SettlementTotal->unit_price,3) }}</th>
                            <th scope="row">$ {{ number_format($settlement->SettlementTotal->batch_price,2) }}</th>
                            <th scope="row">$ {{ number_format($settlement->SettlementTotal->igv,2) }}</th>
                            <th scope="row">$ {{ number_format($settlement->SettlementTotal->detraccion,2) }}</th>
                            <th scope="row">$ {{ number_format($settlement->SettlementTotal->total,2) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</body>
</html>
