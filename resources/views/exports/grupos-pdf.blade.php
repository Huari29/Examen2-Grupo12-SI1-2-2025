<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Grupos</title>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #333;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .meta-info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead tr {
            background-color: #4F46E5;
            color: white;
        }
        
        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            font-size: 11px;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 10px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-activo {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactivo {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .total {
            margin-top: 15px;
            font-weight: bold;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LISTADO DE GRUPOS</h1>
        <p>Sistema de Gestión Académica</p>
    </div>

    <div class="meta-info">
        <strong>Fecha de generación:</strong> {{ $fecha }}<br>
        <strong>Total de registros:</strong> {{ $grupos->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">N°</th>
                <th style="width: 15%;">Código</th>
                <th style="width: 32%;">Nombre</th>
                <th style="width: 15%;">Turno</th>
                <th style="width: 15%;">Gestión</th>
                <th style="width: 15%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $index => $grupo)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $grupo->codigo }}</td>
                    <td>{{ $grupo->nombre }}</td>
                    <td>{{ $grupo->turno }}</td>
                    <td>{{ $grupo->gestion ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $grupo->activo ? 'badge-activo' : 'badge-inactivo' }}">
                            {{ $grupo->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">No hay grupos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        Total de grupos: {{ $grupos->count() }}
    </div>

    <div class="footer">
        <p>Generado el {{ $fecha }} | Sistema de Gestión Académica</p>
    </div>
</body>
</html>