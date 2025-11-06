/**
 * Validaciones del lado del cliente
 * Sistema de Turismo en Coroico
 */

// =====================================================
// Validaciones de Formularios
// =====================================================

const Validaciones = {
    
    /**
     * Validar email
     */
    email: function(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },
    
    /**
     * Validar contraseña (mínimo 6 caracteres)
     */
    password: function(password) {
        return password && password.length >= 6;
    },
    
    /**
     * Validar que dos contraseñas coincidan
     */
    passwordMatch: function(password, passwordConfirm) {
        return password === passwordConfirm;
    },
    
    /**
     * Validar teléfono boliviano
     */
    telefono: function(telefono) {
        // Formato: 7-8 dígitos para celular, o con código de país
        const regex = /^(\+591)?[67]\d{7}$/;
        return regex.test(telefono.replace(/\s/g, ''));
    },
    
    /**
     * Validar que una fecha sea futura
     */
    fechaFutura: function(fecha) {
        const inputDate = new Date(fecha);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return inputDate >= today;
    },
    
    /**
     * Validar número positivo
     */
    numeroPositivo: function(numero) {
        return !isNaN(numero) && parseFloat(numero) > 0;
    },
    
    /**
     * Validar rango de número
     */
    numeroEnRango: function(numero, min, max) {
        const num = parseFloat(numero);
        return !isNaN(num) && num >= min && num <= max;
    },
    
    /**
     * Validar longitud de texto
     */
    longitudTexto: function(texto, min, max) {
        const length = texto.trim().length;
        return length >= min && length <= max;
    },
    
    /**
     * Validar campo requerido
     */
    requerido: function(valor) {
        return valor !== null && valor !== undefined && valor.toString().trim() !== '';
    },
    
    /**
     * Validar calificación (1-5)
     */
    calificacion: function(calificacion) {
        const num = parseInt(calificacion);
        return !isNaN(num) && num >= 1 && num <= 5;
    }
};

// =====================================================
// Aplicar validaciones a formularios específicos
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    
    // =====================================================
    // Formulario de Registro
    // =====================================================
    const formRegistro = document.querySelector('form[action*="usuario/guardar"]');
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = this.querySelector('[name="nombre_completo"]').value;
            const email = this.querySelector('[name="email"]').value;
            const password = this.querySelector('[name="password"]').value;
            const passwordConfirm = this.querySelector('[name="password_confirm"]').value;
            
            let errores = [];
            
            // Validar nombre
            if (!Validaciones.requerido(nombre)) {
                errores.push('El nombre es requerido');
            } else if (!Validaciones.longitudTexto(nombre, 3, 100)) {
                errores.push('El nombre debe tener entre 3 y 100 caracteres');
            }
            
            // Validar email
            if (!Validaciones.requerido(email)) {
                errores.push('El email es requerido');
            } else if (!Validaciones.email(email)) {
                errores.push('El formato del email no es válido');
            }
            
            // Validar contraseña
            if (!Validaciones.password(password)) {
                errores.push('La contraseña debe tener al menos 6 caracteres');
            }
            
            // Validar coincidencia de contraseñas
            if (!Validaciones.passwordMatch(password, passwordConfirm)) {
                errores.push('Las contraseñas no coinciden');
            }
            
            if (errores.length > 0) {
                alert('Errores de validación:\n\n' + errores.join('\n'));
            } else {
                this.submit();
            }
        });
    }
    
    // =====================================================
    // Formulario de Login
    // =====================================================
    const formLogin = document.querySelector('form[action*="usuario/autenticar"]');
    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('[name="email"]').value;
            const password = this.querySelector('[name="password"]').value;
            
            let errores = [];
            
            if (!Validaciones.requerido(email)) {
                errores.push('El email es requerido');
            } else if (!Validaciones.email(email)) {
                errores.push('El formato del email no es válido');
            }
            
            if (!Validaciones.requerido(password)) {
                errores.push('La contraseña es requerida');
            }
            
            if (errores.length > 0) {
                alert('Errores:\n\n' + errores.join('\n'));
            } else {
                this.submit();
            }
        });
    }
    
    // =====================================================
    // Formulario de Reserva
    // =====================================================
    const formReserva = document.querySelector('form[action*="reservas/guardar"]');
    if (formReserva) {
        formReserva.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fecha = this.querySelector('[name="fecha_tour"]').value;
            const personas = this.querySelector('[name="cantidad_personas"]').value;
            
            let errores = [];
            
            // Validar fecha
            if (!Validaciones.requerido(fecha)) {
                errores.push('La fecha del tour es requerida');
            } else if (!Validaciones.fechaFutura(fecha)) {
                errores.push('La fecha debe ser futura');
            }
            
            // Validar cantidad de personas
            if (!Validaciones.numeroPositivo(personas)) {
                errores.push('La cantidad de personas debe ser mayor a 0');
            }
            
            if (errores.length > 0) {
                alert('Errores:\n\n' + errores.join('\n'));
            } else {
                // Confirmar reserva
                const confirmar = confirm('¿Confirmar esta reserva?');
                if (confirmar) {
                    this.submit();
                }
            }
        });
    }
    
    // =====================================================
    // Formulario de Comentario
    // =====================================================
    const formComentario = document.querySelector('form[action*="comentarios/crear"], form[action*="comentarios/actualizar"]');
    if (formComentario) {
        formComentario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const calificacionInput = this.querySelector('[name="calificacion"]:checked');
            const comentario = this.querySelector('[name="comentario"]').value;
            
            let errores = [];
            
            // Validar calificación
            if (!calificacionInput) {
                errores.push('Debes seleccionar una calificación');
            } else {
                const calificacion = calificacionInput.value;
                if (!Validaciones.calificacion(calificacion)) {
                    errores.push('La calificación debe estar entre 1 y 5');
                }
            }
            
            // Validar comentario
            if (!Validaciones.requerido(comentario)) {
                errores.push('El comentario es requerido');
            } else if (!Validaciones.longitudTexto(comentario, 10, 1000)) {
                errores.push('El comentario debe tener entre 10 y 1000 caracteres');
            }
            
            if (errores.length > 0) {
                alert('Errores:\n\n' + errores.join('\n'));
            } else {
                this.submit();
            }
        });
    }
    
    // =====================================================
    // Formularios de Lugares y Tours (Admin)
    // =====================================================
    const formLugar = document.querySelector('form[action*="lugares/guardar"], form[action*="lugares/actualizar"]');
    if (formLugar) {
        formLugar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = this.querySelector('[name="nombre"]').value;
            const descripcion = this.querySelector('[name="descripcion"]').value;
            const precio = this.querySelector('[name="precio_entrada"]').value;
            
            let errores = [];
            
            if (!Validaciones.requerido(nombre)) {
                errores.push('El nombre es requerido');
            }
            
            if (!Validaciones.requerido(descripcion)) {
                errores.push('La descripción es requerida');
            } else if (!Validaciones.longitudTexto(descripcion, 20, 2000)) {
                errores.push('La descripción debe tener entre 20 y 2000 caracteres');
            }
            
            if (!Validaciones.numeroPositivo(precio) && precio != 0) {
                errores.push('El precio debe ser un número válido');
            }
            
            if (errores.length > 0) {
                alert('Errores:\n\n' + errores.join('\n'));
            } else {
                this.submit();
            }
        });
    }
    
    const formTour = document.querySelector('form[action*="tours/guardar"], form[action*="tours/actualizar"]');
    if (formTour) {
        formTour.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = this.querySelector('[name="nombre"]').value;
            const descripcion = this.querySelector('[name="descripcion"]').value;
            const precio = this.querySelector('[name="precio"]').value;
            const cupo = this.querySelector('[name="cupo_maximo"]').value;
            
            let errores = [];
            
            if (!Validaciones.requerido(nombre)) {
                errores.push('El nombre es requerido');
            }
            
            if (!Validaciones.requerido(descripcion)) {
                errores.push('La descripción es requerida');
            }
            
            if (!Validaciones.numeroPositivo(precio)) {
                errores.push('El precio debe ser mayor a 0');
            }
            
            if (!Validaciones.numeroPositivo(cupo)) {
                errores.push('El cupo debe ser mayor a 0');
            }
            
            if (errores.length > 0) {
                alert('Errores:\n\n' + errores.join('\n'));
            } else {
                this.submit();
            }
        });
    }
    
    // =====================================================
    // Validación en tiempo real de email
    // =====================================================
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !Validaciones.email(this.value)) {
                this.style.borderColor = '#dc3545';
                this.setCustomValidity('Email inválido');
            } else {
                this.style.borderColor = '';
                this.setCustomValidity('');
            }
        });
    });
    
    // =====================================================
    // Validación en tiempo real de contraseñas coincidentes
    // =====================================================
    const passwordInputs = document.querySelectorAll('input[name="password"]');
    passwordInputs.forEach(input => {
        const confirmInput = input.form.querySelector('input[name="password_confirm"]');
        if (confirmInput) {
            const validatePasswords = () => {
                if (confirmInput.value) {
                    if (input.value === confirmInput.value) {
                        confirmInput.style.borderColor = '#28a745';
                        confirmInput.setCustomValidity('');
                    } else {
                        confirmInput.style.borderColor = '#dc3545';
                        confirmInput.setCustomValidity('Las contraseñas no coinciden');
                    }
                }
            };
            
            input.addEventListener('input', validatePasswords);
            confirmInput.addEventListener('input', validatePasswords);
        }
    });
    
    console.log('✅ Validaciones cargadas');
});

// Exportar para uso global
window.Validaciones = Validaciones;