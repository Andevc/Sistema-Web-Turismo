/**
 * Manejo de LocalStorage y SessionStorage
 * Sistema de Turismo en Coroico
 */

// =====================================================
// LocalStorage - Datos persistentes
// =====================================================

const Storage = {
    
    /**
     * Guardar datos en localStorage
     */
    set: function(key, value) {
        try {
            const serializedValue = JSON.stringify(value);
            localStorage.setItem(key, serializedValue);
            return true;
        } catch (error) {
            console.error('Error al guardar en localStorage:', error);
            return false;
        }
    },
    
    /**
     * Obtener datos de localStorage
     */
    get: function(key) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : null;
        } catch (error) {
            console.error('Error al leer de localStorage:', error);
            return null;
        }
    },
    
    /**
     * Eliminar un item de localStorage
     */
    remove: function(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (error) {
            console.error('Error al eliminar de localStorage:', error);
            return false;
        }
    },
    
    /**
     * Limpiar todo el localStorage
     */
    clear: function() {
        try {
            localStorage.clear();
            return true;
        } catch (error) {
            console.error('Error al limpiar localStorage:', error);
            return false;
        }
    },
    
    /**
     * Verificar si existe una clave
     */
    has: function(key) {
        return localStorage.getItem(key) !== null;
    }
};

// =====================================================
// SessionStorage - Datos de sesión
// =====================================================

const SessionStore = {
    
    /**
     * Guardar datos en sessionStorage
     */
    set: function(key, value) {
        try {
            const serializedValue = JSON.stringify(value);
            sessionStorage.setItem(key, serializedValue);
            return true;
        } catch (error) {
            console.error('Error al guardar en sessionStorage:', error);
            return false;
        }
    },
    
    /**
     * Obtener datos de sessionStorage
     */
    get: function(key) {
        try {
            const item = sessionStorage.getItem(key);
            return item ? JSON.parse(item) : null;
        } catch (error) {
            console.error('Error al leer de sessionStorage:', error);
            return null;
        }
    },
    
    /**
     * Eliminar un item de sessionStorage
     */
    remove: function(key) {
        try {
            sessionStorage.removeItem(key);
            return true;
        } catch (error) {
            console.error('Error al eliminar de sessionStorage:', error);
            return false;
        }
    },
    
    /**
     * Limpiar todo el sessionStorage
     */
    clear: function() {
        try {
            sessionStorage.clear();
            return true;
        } catch (error) {
            console.error('Error al limpiar sessionStorage:', error);
            return false;
        }
    }
};

// =====================================================
// Funcionalidades específicas de la aplicación
// =====================================================

/**
 * Gestión de Favoritos
 */
const Favorites = {
    KEY: 'turismo_favoritos',
    
    /**
     * Agregar a favoritos
     */
    add: function(lugarId) {
        const favorites = this.getAll();
        if (!favorites.includes(lugarId)) {
            favorites.push(lugarId);
            Storage.set(this.KEY, favorites);
            return true;
        }
        return false;
    },
    
    /**
     * Remover de favoritos
     */
    remove: function(lugarId) {
        const favorites = this.getAll();
        const index = favorites.indexOf(lugarId);
        if (index > -1) {
            favorites.splice(index, 1);
            Storage.set(this.KEY, favorites);
            return true;
        }
        return false;
    },
    
    /**
     * Obtener todos los favoritos
     */
    getAll: function() {
        return Storage.get(this.KEY) || [];
    },
    
    /**
     * Verificar si un lugar está en favoritos
     */
    has: function(lugarId) {
        return this.getAll().includes(lugarId);
    },
    
    /**
     * Contar favoritos
     */
    count: function() {
        return this.getAll().length;
    }
};

/**
 * Gestión de Filtros de Búsqueda
 */
const SearchFilters = {
    KEY: 'turismo_filtros_busqueda',
    
    /**
     * Guardar filtros actuales
     */
    save: function(filters) {
        SessionStore.set(this.KEY, filters);
    },
    
    /**
     * Obtener filtros guardados
     */
    get: function() {
        return SessionStore.get(this.KEY);
    },
    
    /**
     * Limpiar filtros
     */
    clear: function() {
        SessionStore.remove(this.KEY);
    },
    
    /**
     * Aplicar filtros al formulario
     */
    applyToForm: function(formId) {
        const filters = this.get();
        if (filters) {
            const form = document.getElementById(formId);
            if (form) {
                Object.keys(filters).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = filters[key];
                    }
                });
            }
        }
    }
};

/**
 * Gestión de Última Búsqueda
 */
const RecentSearches = {
    KEY: 'turismo_busquedas_recientes',
    MAX_ITEMS: 5,
    
    /**
     * Agregar búsqueda reciente
     */
    add: function(searchTerm) {
        if (!searchTerm || searchTerm.trim() === '') return;
        
        const searches = this.getAll();
        
        // Remover si ya existe
        const index = searches.indexOf(searchTerm);
        if (index > -1) {
            searches.splice(index, 1);
        }
        
        // Agregar al inicio
        searches.unshift(searchTerm);
        
        // Limitar cantidad
        if (searches.length > this.MAX_ITEMS) {
            searches.pop();
        }
        
        Storage.set(this.KEY, searches);
    },
    
    /**
     * Obtener todas las búsquedas recientes
     */
    getAll: function() {
        return Storage.get(this.KEY) || [];
    },
    
    /**
     * Limpiar búsquedas recientes
     */
    clear: function() {
        Storage.remove(this.KEY);
    }
};

/**
 * Gestión de Preferencias de Usuario
 */
const UserPreferences = {
    KEY: 'turismo_preferencias',
    
    /**
     * Guardar preferencia
     */
    set: function(key, value) {
        const prefs = this.getAll();
        prefs[key] = value;
        Storage.set(this.KEY, prefs);
    },
    
    /**
     * Obtener preferencia
     */
    get: function(key, defaultValue = null) {
        const prefs = this.getAll();
        return prefs.hasOwnProperty(key) ? prefs[key] : defaultValue;
    },
    
    /**
     * Obtener todas las preferencias
     */
    getAll: function() {
        return Storage.get(this.KEY) || {};
    },
    
    /**
     * Remover preferencia
     */
    remove: function(key) {
        const prefs = this.getAll();
        delete prefs[key];
        Storage.set(this.KEY, prefs);
    }
};

/**
 * Gestión de Carrito de Reservas Temporal
 */
const ReservationCart = {
    KEY: 'turismo_carrito_reserva',
    
    /**
     * Agregar reserva al carrito
     */
    add: function(tourId, tourName, date, persons, price) {
        const item = {
            tourId: tourId,
            tourName: tourName,
            date: date,
            persons: persons,
            pricePerPerson: price,
            total: price * persons,
            addedAt: new Date().toISOString()
        };
        Storage.set(this.KEY, item);
        return item;
    },
    
    /**
     * Obtener reserva del carrito
     */
    get: function() {
        return Storage.get(this.KEY);
    },
    
    /**
     * Limpiar carrito
     */
    clear: function() {
        Storage.remove(this.KEY);
    },
    
    /**
     * Verificar si hay items en el carrito
     */
    hasItems: function() {
        return this.get() !== null;
    }
};

/**
 * Gestión de Historial de Navegación
 */
const NavigationHistory = {
    KEY: 'turismo_historial_navegacion',
    MAX_ITEMS: 10,
    
    /**
     * Agregar página al historial
     */
    add: function(url, title) {
        const history = this.getAll();
        
        const item = {
            url: url,
            title: title,
            timestamp: new Date().toISOString()
        };
        
        // Evitar duplicados consecutivos
        if (history.length > 0 && history[0].url === url) {
            return;
        }
        
        history.unshift(item);
        
        if (history.length > this.MAX_ITEMS) {
            history.pop();
        }
        
        SessionStore.set(this.KEY, history);
    },
    
    /**
     * Obtener historial
     */
    getAll: function() {
        return SessionStore.get(this.KEY) || [];
    },
    
    /**
     * Limpiar historial
     */
    clear: function() {
        SessionStore.remove(this.KEY);
    }
};

// =====================================================
// Inicialización al cargar la página
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    
    // Agregar página actual al historial
    NavigationHistory.add(
        window.location.pathname,
        document.title
    );
    
    // Restaurar filtros de búsqueda si existen
    const searchForm = document.querySelector('form[action*="lugares"], form[action*="tours"]');
    if (searchForm) {
        const savedFilters = SearchFilters.get();
        if (savedFilters) {
            // Aplicar filtros guardados (opcional)
            console.log('Filtros guardados encontrados:', savedFilters);
        }
        
        // Guardar filtros al enviar formulario
        searchForm.addEventListener('submit', function() {
            const formData = new FormData(this);
            const filters = {};
            formData.forEach((value, key) => {
                if (value) filters[key] = value;
            });
            SearchFilters.save(filters);
        });
    }
    
    // Mostrar indicador de carrito si hay items
    if (ReservationCart.hasItems()) {
        console.log('Carrito de reserva activo:', ReservationCart.get());
    }
    
    // Botones de favoritos (si existen)
    const favoriteButtons = document.querySelectorAll('[data-favorite]');
    favoriteButtons.forEach(button => {
        const lugarId = parseInt(button.getAttribute('data-favorite'));
        
        // Actualizar estado visual
        if (Favorites.has(lugarId)) {
            button.classList.add('active');
            button.innerHTML = '❤️';
        } else {
            button.innerHTML = '🤍';
        }
        
        // Manejar click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (Favorites.has(lugarId)) {
                Favorites.remove(lugarId);
                this.classList.remove('active');
                this.innerHTML = '🤍';
                showNotification('Removido de favoritos', 'info');
            } else {
                Favorites.add(lugarId);
                this.classList.add('active');
                this.innerHTML = '❤️';
                showNotification('Agregado a favoritos', 'success');
            }
        });
    });
    
    console.log('✅ Storage module cargado');
    console.log('📊 Estadísticas:', {
        favoritos: Favorites.count(),
        busquedasRecientes: RecentSearches.getAll().length,
        carritoActivo: ReservationCart.hasItems()
    });
});

// Exportar para uso global
window.TurismoStorage = {
    Storage,
    SessionStore,
    Favorites,
    SearchFilters,
    RecentSearches,
    UserPreferences,
    ReservationCart,
    NavigationHistory
};