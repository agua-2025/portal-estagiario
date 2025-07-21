{{-- resources/views/emails/contact-form.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagem Fale Conosco</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .header {
            background-color: #ffffff;
            padding: 32px 32px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .header h1 {
            color: #111827;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        
        .header p {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 24px 0;
        }
        
        .content {
            padding: 32px;
        }
        
        .field {
            margin-bottom: 24px;
        }
        
        .field:last-of-type {
            margin-bottom: 32px;
        }
        
        .field-label {
            font-weight: 500;
            color: #374151;
            font-size: 14px;
            margin-bottom: 4px;
            display: block;
        }
        
        .field-value {
            color: #111827;
            font-size: 15px;
        }
        
        .message-content {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
            white-space: pre-wrap;
            font-size: 15px;
            line-height: 1.5;
            color: #374151;
        }
        
        .notice {
            background-color: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 6px;
            padding: 16px;
            font-size: 14px;
            color: #0369a1;
            margin-top: 24px;
        }
        
        .footer {
            background-color: #f9fafb;
            padding: 24px 32px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
        }
        
        .footer p {
            color: #6b7280;
            font-size: 13px;
            margin: 0;
        }
        
        /* Responsividade */
        @media (max-width: 600px) {
            body {
                padding: 20px 16px;
            }
            
            .header {
                padding: 24px 24px 0;
            }
            
            .content {
                padding: 24px;
            }
            
            .footer {
                padding: 20px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nova Mensagem de Contato</h1>
            <p>Formulário de contato do Portal do Estagiário</p>
        </div>
        
        <div class="content">
            <div class="field">
                <label class="field-label">Nome</label>
                <div class="field-value">{{ $name }}</div>
            </div>
            
            <div class="field">
                <label class="field-label">E-mail</label>
                <div class="field-value">{{ $email }}</div>
            </div>
            
            <div class="field">
                <label class="field-label">Assunto</label>
                <div class="field-value">{{ $subject }}</div>
            </div>
            
            <div class="field">
                <label class="field-label">Mensagem</label>
                <div class="message-content">{{ $messageContent }}</div>
            </div>
            
            <div class="notice">
                Esta mensagem foi enviada através do formulário de contato do Portal do Estagiário.
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Portal do Estagiário. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>