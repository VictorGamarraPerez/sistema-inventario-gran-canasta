<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
            color: #333;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .verification-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 10px;
            text-align: center;
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .instructions {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin: 20px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 5px;
        }
        .warning-text {
            color: #856404;
            font-size: 14px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">🛡️</div>
            <h1>La Gran Canasta</h1>
            <p>Verificación de Seguridad</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                ¡Hola {{ $userName }}!
            </div>
            
            <p class="instructions">
                Has iniciado sesión en el <strong>Sistema de Inventario de La Gran Canasta</strong>. 
                Para completar el proceso de autenticación, utiliza el siguiente código de verificación:
            </p>
            
            <div class="verification-code">
                {{ $code }}
            </div>
            
            <p class="instructions">
                Este código es válido por <strong>10 minutos</strong>. Ingresa este código en la pantalla 
                de verificación para acceder al sistema.
            </p>
            
            <div class="warning">
                <div class="warning-title">⚠️ Importante</div>
                <div class="warning-text">
                    Si no solicitaste este código, ignora este correo o contacta al administrador del sistema 
                    inmediatamente. Nunca compartas este código con nadie.
                </div>
            </div>
            
            <p class="instructions" style="margin-top: 30px; font-size: 14px;">
                <strong>Consejos de seguridad:</strong>
            </p>
            <ul style="font-size: 14px; color: #555; line-height: 1.8;">
                <li>No compartas este código con nadie</li>
                <li>Verifica que el remitente sea legítimo</li>
                <li>Si no reconoces esta actividad, cambia tu contraseña inmediatamente</li>
            </ul>
        </div>
        
        <div class="email-footer">
            <p>
                <strong>La Gran Canasta</strong><br>
                Sistema de Control de Inventario<br>
                © {{ date('Y') }} Todos los derechos reservados
            </p>
            <p style="margin-top: 15px;">
                Este es un correo automático, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
