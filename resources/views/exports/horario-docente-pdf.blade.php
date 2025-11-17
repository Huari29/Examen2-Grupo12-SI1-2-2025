<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Docente - {{ $docente->nombre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-item {
            display: inline-block;
            margin-right: 20px;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #6b46c1;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #333;
        }
        
        td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            min-height: 60px;
        }
        
        .hora-column {
            background-color: #f7fafc;
            font-weight: bold;
            width: 80px;
        }
        
        .clase-info {
            background-color: #faf5ff;
            padding: 5px;
            border-radius: 3px;
        }
        
        .materia {
            font-weight: bold;
            color: #2d3748;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .grupo {
            color: #4a5568;
            font-size: 9px;
            margin-bottom: 2px;
        }
        
        .aula {
            color: #718096;
            font-size: 9px;
            font-style: italic;
        }
        
        .empty-cell {
            background-color: #f7fafc;
            color: #cbd5e0;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HORARIO DE CLASES - DOCENTE</h1>
        <p>Sistema de Gestión Académica</p>
    </div>

    <div class="info-section">
        <span class="info-item">
            <span class="info-label">Docente:</span> {{ $docente->nombre }}
        </span>
        <span class="info-item">
            <span class="info-label">Correo:</span> {{ $docente->correo }}
        </span>
        <span class="info-item">
            <span class="info-label">Gestión:</span> {{ $gestion }}
        </span>
        <span class="info-item">
            <span class="info-label">Fecha:</span> {{ now()->format('d/m/Y') }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="hora-column">HORA</th>
                @foreach($dias as $dia)
                    <th>{{ $dia }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($horarios as $horario)
                <tr>
                    <td class="hora-column">
                        {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}<br>
                        {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                    </td>
                    
                    @foreach($dias as $dia)
                        <td>
                            @if(isset($horarioOrganizado[$dia][$horario->id]))
                                @php
                                    $detalle = $horarioOrganizado[$dia][$horario->id];
                                @endphp
                                <div class="clase-info">
                                    <div class="materia">{{ $detalle->materiaGrupo->materia->codigo }}</div>
                                    <div class="materia">{{ $detalle->materiaGrupo->materia->nombre }}</div>
                                    <div class="grupo">Grupo: {{ $detalle->materiaGrupo->grupo->codigo }}</div>
                                    <div class="aula">Aula: {{ $detalle->aula->codigo }}</div>
                                </div>
                            @else
                                <div class="empty-cell">—</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión Académica</p>
    </div>
</body>
</html>