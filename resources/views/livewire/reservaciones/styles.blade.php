<link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet" > 
<style>
/* tipografia */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap');

:root {
    --primary: #FF66A3;
    --primary-hover: #FF4D94;
    --text: #333333;
    --text-light: #666666;
    --background: #F5F5F5;
    --transition: all 0.3s ease;
    --color-uno: #56021F;
    --color-dos: #7D1C4A;
    --color-tres: #D17D98;
    --color-fondo: #F4CCE9;
    --color-texto: #ffffff;
}

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, var(--color-fondo), var(--color-tres));
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Estilos del Quiz */
.quiz-container {
    width: 100%;
    max-width: 450px;
    margin: 15px;
    background: rgba(255, 255, 255, 0.9);
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    transition: var(--transition);
    max-height: 90vh;
    overflow-y: auto;
    box-sizing: border-box;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.question {
    display: none;
    animation: fadeIn 0.4s ease-out;
}

.question.active {
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    color: var(--text);
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    text-align: center;
    letter-spacing: 1px;
}

h4 {
    font-weight: 600;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    text-align: center;
    color: var(--text);
}

.btn-custom {
    background: linear-gradient(135deg, var(--color-uno), var(--color-dos));
    color: var(--color-texto);
    padding: 12px 28px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    width: auto;
    margin-top: 1rem;
    cursor: pointer;
}

.btn-custom:hover, .btn-custom:focus {
    background: var(--color-dos);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

/* Estilos del formulario de reserva */
.contenedor {
    display: flex;
    flex-wrap: wrap;
    max-width: 1000px;
    width: 90%;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.formulario {
    flex: 1;
    padding: 40px;
    background: rgba(215, 125, 152, 0.9);
    color: var(--color-texto);
    border-radius: 15px 0 0 15px;
}

.c1 {
    font-weight: 600;
    display: block;
    margin-top: 12px;
    font-size: 16px;
}

.c2 {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid var(--color-dos);
    font-size: 16px;
    outline: none;
    background: rgba(255, 255, 255, 0.7);
    transition: 0.3s;
}

.c2:focus {
    border: 2px solid var(--color-uno);
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

.Casilla2 {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-dos);
    padding: 20px;
    border-radius: 0 15px 15px 0;
}

.Casilla2 img {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* Componentes compartidos */
.btn {
    width: 100%;
    background: linear-gradient(135deg, var(--color-texto), var(--color-texto));
    color: var(--color-uno);
    padding: 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    transition: 0.3s ease;
}

.btn:hover {
    background: var(--color-dos);
    color: var(--color-texto);
    transform: scale(1.07);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.hair-type-options {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.hair-type-option {
    width: 100px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: var(--transition);
    border: 2px solid transparent;
}

.hair-type-option:hover {
    transform: translateY(-5px);
    border-color: var(--color-dos);
}

.hair-type-option img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hair-option {
    width: 100%;
    max-width: 60px;
    height: auto;
    padding: 4px;
    margin: 2px;
    border-radius: 8px;
    transition: var(--transition);
    background-color: white;
    border: 2px solid var(--color-dos);
}

.hair-option:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.hair-image {
    width: 100%;
    max-width: 45px;
    height: auto;
    object-fit: contain;
}
/* Animaciones */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media screen and (max-width: 768px) {
    .contenedor {
        flex-direction: column;
    }
    
    .formulario {
        border-radius: 15px 15px 0 0;
        padding: 30px;
    }
    
    .Casilla2 {
        border-radius: 0 0 15px 15px;
        height: 250px;
    }

    .quiz-container {
        padding: 1.5rem;
        margin: 10px;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    h4 {
        font-size: 1.1rem;
    }
}
</style>