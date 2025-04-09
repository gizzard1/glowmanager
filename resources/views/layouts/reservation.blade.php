<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowManager - Experiencia de Belleza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('livewire.reservaciones.styles')

    <livewire:styles />
</head>
<body>
    {{ $slot ?? '' }}

    @include('livewire.reservaciones.js')
    <livewire:scripts />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>