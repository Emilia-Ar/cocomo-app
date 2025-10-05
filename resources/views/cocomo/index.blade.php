@extends('layouts.app')

@section('title', 'Estimador COCOMO I (Intermedio)')

@section('content')
<div class="card">
  <form action="{{ route('cocomo.estimate') }}" method="POST">
    @csrf
    <div class="grid grid-3">
      <div>
        <label for="kloc">KLOC (miles de líneas de código)</label>
        <input type="number" step="0.001" name="kloc" id="kloc" value="{{ old('kloc', 10) }}">
        @error('kloc')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <label for="mode">Modo del proyecto</label>
        <select name="mode" id="mode">
          @foreach(['organico'=>'Orgánico','semiacoplado'=>'Semiacoplado','empotrado'=>'Empotrado'] as $k=>$v)
            <option value="{{ $k }}" @selected(old('mode','organico')===$k)>{{ $v }}</option>
          @endforeach
        </select>
        @error('mode')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <label for="salary">Salario mensual promedio ($)</label>
        <input type="number" step="0.01" name="salary" id="salary" value="{{ old('salary', 1000) }}">
        @error('salary')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <hr style="margin:16px 0">
    <h3>Factores de costo (15)</h3>
    <p class="muted">Selecciona el nivel para cada driver. Por defecto: nominal.</p>

    @php($levels=['muy_bajo'=>'Muy Bajo','bajo'=>'Bajo','nominal'=>'Nominal','alto'=>'Alto','muy_alto'=>'Muy Alto','extra_alto'=>'Extra Alto'])
    @php($drivers=['RELY','DATA','CPLX','TIME','STOR','VIRT','TURN','ACAP','AEXP','PCAP','VEXP','LTEX','MODP','TOOL','SCED'])
    <div class="grid grid-3">
      @foreach($drivers as $d)
        <div>
          <label>{{ $d }}</label>
          <select name="{{ $d }}">
            @foreach($levels as $k=>$v)
              <option value="{{ $k }}" @selected(old($d, $defaults[$d] ?? 'nominal')===$k)>{{ $v }}</option>
            @endforeach
          </select>
          @error($d)<div class="err">{{ $message }}</div>@enderror
        </div>
      @endforeach
    </div>

    <div style="margin-top:16px">
      <button class="btn" type="submit">Calcular</button>
      <a class="btn secondary" href="{{ route('cocomo.export') }}">Exportar CSV</a>
    </div>
  </form>
</div>

@if(session('result'))
  <div class="card">
    <h3>Resultado</h3>
    @php($r=session('result'))
    <table>
      <tr><th>EAF</th><td>{{ $r['eaf'] }}</td></tr>
      <tr><th>PM (persona-meses)</th><td class="ok">{{ $r['pm'] }}</td></tr>
      <tr><th>Duración (meses)</th><td>{{ $r['tdev'] }}</td></tr>
      <tr><th>Personal promedio (P)</th><td>{{ $r['p'] }}</td></tr>
      <tr><th>Costo mensual (P × salario)</th><td>${{ number_format($r['monthly'],2,'.',',') }}</td></tr>
      <tr><th>Costo total (PM × salario)</th><td>${{ number_format($r['total'],2,'.',',') }}</td></tr>
    </table>
  </div>
@endif

<div class="card">
  <h3>Historial</h3>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Fecha</th><th>KLOC</th><th>Modo</th><th>Salario</th><th>EAF</th><th>PM</th><th>Meses</th><th>P</th><th>Total $</th>
      </tr>
    </thead>
    <tbody>
      @foreach($history as $item)
        <tr>
          <td>{{ $item->id }}</td>
          <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
          <td>{{ $item->kloc }}</td>
          <td>{{ ucfirst($item->mode) }}</td>
          <td>${{ $item->salary }}</td>
          <td>{{ $item->eaf }}</td>
          <td>{{ $item->pm }}</td>
          <td>{{ $item->tdev }}</td>
          <td>{{ $item->p }}</td>
          <td>${{ $item->total_cost }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div style="margin-top:10px">{{ $history->links() }}</div>
</div>
@endsection



