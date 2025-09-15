<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reservación</title>
    <style>
        /* Reset de estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 18px;
            color: #2575fc;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2575fc;
        }

        .info-label {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .seats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .seat {
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .total {
            background-color: #e8f4ff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 10px;
        }

        .total-amount {
            font-size: 32px;
            font-weight: 700;
            color: #2575fc;
            margin: 10px 0;
        }

        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(37, 117, 252, 0.3);
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
            border: 10px solid #f0f0f0;
            border-radius: 10px;
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 20px;
            }

            .header {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>¡Reservación Confirmada!</h1>
            <p>Tu experiencia cinematográfica está lista</p>
        </div>

        <div class="content">
            <div class="section">
                <h2 class="section-title">Detalles de la Reservación</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Email del Cliente</div>
                        <div class="info-value">{{ $reservation->user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Número de Sala</div>
                        <div class="info-value">{{ $reservation->showtime->room->number }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Asientos Reservados</h2>
                <div class="seats-container">
                    @foreach ($reservation->seats as $seat)
                        <div class="seat">
                            {{ $seat }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Resumen del Pago</h2>
                <div class="total">
                    <div>Monto Total Pagado</div>
                    <div class="total-amount">{{ $reservation->amount }} Gs.</div>
                    <div>¡Gracias por tu compra!</div>
                </div>
            </div>

            <div class="qr-code">
                {{-- <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTIwIDEyMCI+PHJlY3Qgd2lkdGg9IjEyMCIgaGVpZ2h0PSIxMjAiIGZpbGw9IiNmZmYiLz48cmVjdCB4PSIxMCIgeT0iMTAiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0iIzAwMCIvPjxyZWN0IHg9IjUwIiB5PSIxMCIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBmaWxsPSIjMDAwIi8+PHJlY3QgeD0iOTAiIHk9IjEwIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiMwMDAiLz48cmVjdCB4PSIxMCIgeT0iNTAiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0iIzAwMCIvPjxyZWN0IHg9IjUwIiB5PSI1MCIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBmaWxsPSIjMDAwIi8+PHJlY3QgeD0iOTAiIHk9IjUwIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiMwMDAiLz48cmVjdCB4PSIxMCIgeT0iOTAiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0iIzAwMCIvPjxyZWN0IHg9IjUwIiB5PSI5MCIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBmaWxsPSIjMDAwIi8+PHJlY3QgeD0iOTAiIHk9IjkwIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiMwMDAiLz48L3N2Zz4="
                    alt="Código QR"> --}}
                <img src="{{ asset("qrcode/$reservation->code.png") }}" alt="QR">

                <p>Presenta este código QR en la entrada</p>
            </div>

            <div style="text-align: center;">
                <a href="#" class="btn">Ver Detalles de la Reservación</a>
            </div>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>© 2023 CineWorld. Todos los derechos reservados.</p>
            <p>Av. Principal 123, Ciudad de México</p>
        </div>
    </div>
</body>

</html>
