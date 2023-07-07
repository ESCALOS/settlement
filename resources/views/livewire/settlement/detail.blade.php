<x-modal.card title="Liquidación {{ $settlement?->batch }}" blur fullscreen wire:model.defer="open">
    @if ($settlement)
    <div id="Pagables">
        <div class="w-full py-2 mt-4 bg-blue-800">
            <h1 class="text-xl font-black text-center text-white">PAGABLES</h1>
        </div>
        <table style="font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;font-size: 15px">
            <thead class="text-white bg-gray-600">
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Metal</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Leyes</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">%Pagable</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Deducción</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Precio</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">US$/Tm</th>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Cu %</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->Law->copper) }}%</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->PercentagePayable->copper) }}%</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->Deduction->copper) }}%</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->unit_price_copper,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->total_price_copper,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Ag Oz/TC</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floor($settlement->Law->silver*$settlement->Law->silver_factor*1000)/1000 }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->PercentagePayable->silver) }}%</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->Deduction->silver) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->unit_price_silver,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->total_price_silver,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Au Oz/TC</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floor($settlement->Law->gold*$settlement->Law->gold_factor*1000)/1000 }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->PercentagePayable->gold) }}%</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">{{ floatval($settlement->Deduction->gold) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->unit_price_gold,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PayableTotal->total_price_gold,3) }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="border: 2px solid black;padding: 1rem;text-align: center;font-weight:bolder">Total Pagable / TM</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->payable_total,3) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="Deducciones">
        <div class="w-full py-2 mt-4 bg-amber-600">
            <h1 class="text-xl font-black text-center text-white">DEDUCCIONES</h1>
        </div>
        <table style="font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;font-size: 15px">
            <thead class="text-white bg-gray-600">
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Concepto</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Precio</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">US$/Tm</th>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Refinación de Cu</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->unit_price_copper,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->total_price_copper,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Refinación de Ag</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->unit_price_silver,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->total_price_silver,3) }}</td>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Refinación de Au</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->unit_price_gold,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->total_price_gold,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Maquila</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->maquila,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->maquila,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Análisis</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->analysis,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->analysis,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Estibadores</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->stevedore,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->DeductionTotal->stevedore,3) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 2px solid black;padding: 1rem;text-align: center;font-weight:bolder">Total Deducciones</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->deduction_total,3) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="Penalidades">
        <div class="w-full py-2 mt-4 bg-red-600">
            <h1 class="text-xl font-bold text-center text-white">PENALIDADES</h1>
        </div>
        <table style="font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;font-size: 15px">
            <thead class="text-white bg-gray-600">
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Elemento</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Penalidad</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Máximo Permitido</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">US$/Tm</th>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">As</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->arsenic,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->arsenic,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_arsenic,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Sb</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->antomony,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->antomony,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_antomony,3) }}</td>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Bi</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->bismuth,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->bismuth,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_bismuth,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Pb</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->lead,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->lead,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_lead,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Zn</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->zinc,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->zinc,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_zinc,3) }}</td>
                </tr>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">Hg</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->Penalty->mercury,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->AllowedAmount->mercury,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->PenaltyTotal->total_mercury,3) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 2px solid black;padding: 1rem;text-align: center;font-weight:bolder">Total Penalidades</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->penalty_total,3) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="Total_Liquidacion">
        <div class="w-full py-2 mt-4 bg-green-600">
            <h1 class="text-xl font-black text-center text-white">TOTAL</h1>
        </div>
        <table style="font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;font-size: 15px">
            <thead class="text-white bg-gray-600">
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Total US$/TM</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Valor por Lote US$</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">IGV</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Valor por Lote US$ + IGV</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Detracción</th>
                <th style="border: 2px solid black;padding: 1rem;text-align: center">Total de liquidación</th>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->unit_price,3) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->batch_price,2) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->igv,2) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->batch_price+$settlement->SettlementTotal->igv,2) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->detraccion,2) }}</td>
                    <td style="border: 2px solid black;padding: 1rem;text-align: center">$ {{ number_format($settlement->SettlementTotal->total,2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <div class="flex">
                <x-button flat label="{{ __('Cancel') }}" x-on:click="close" />
                <x-button label="Exportar PDF" spinner="printSettlement" rose icon="printer" wire:click='printSettlement'/>
            </div>
        </div>
    </x-slot>
</x-modal.card>
