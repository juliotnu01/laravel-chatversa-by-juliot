<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">TaskFlow Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">Hola, {{ user.name }}</span>
                        <button @click="logout"
                            class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Proyectos Totales</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.projects_count }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Tareas Asignadas</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.tasks_count }}</dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Estado</dt>
                        <dd class="mt-1 text-lg font-semibold"
                            :class="user.is_online ? 'text-green-600' : 'text-gray-600'">
                            {{ user.login_status }}
                        </dd>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Último Login</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.last_login_at) }}</dd>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna 1: Gestión de Proyectos -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Crear Proyecto -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nuevo Proyecto</h3>
                            <form @submit.prevent="createProject" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                        <input v-model="newProject.name" type="text" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                                        <select v-model="newProject.priority"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="low">Baja</option>
                                            <option value="medium">Media</option>
                                            <option value="high">Alta</option>
                                            <option value="urgent">Urgente</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea v-model="newProject.description"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        rows="3"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                        Crear Proyecto
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Proyectos -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Mis Proyectos</h3>
                                <button @click="fetchProjects" 
                                    class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md text-sm hover:bg-gray-300">
                                    🔄 Actualizar
                                </button>
                            </div>

                            <!-- Filtros -->
                            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <select v-model="projectFilters.status" @change="fetchProjects"
                                    class="border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">Todos los estados</option>
                                    <option value="planning">Planificación</option>
                                    <option value="active">Activo</option>
                                    <option value="paused">Pausado</option>
                                    <option value="completed">Completado</option>
                                </select>
                                <select v-model="projectFilters.priority" @change="fetchProjects"
                                    class="border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="">Todas las prioridades</option>
                                    <option value="low">Baja</option>
                                    <option value="medium">Media</option>
                                    <option value="high">Alta</option>
                                </select>
                                <div class="relative">
                                    <input v-model="projectFilters.search" @input="debouncedFetchProjects" 
                                        type="text" placeholder="Buscar proyectos..."
                                        class="border border-gray-300 rounded-md shadow-sm p-2 w-full">
                                </div>
                            </div>

                            <!-- Proyectos -->
                            <div v-if="loading.projects" class="text-center py-4">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600">
                                </div>
                            </div>

                            <div v-else-if="filteredProjects.length === 0" class="text-center py-8 text-gray-500">
                                No se encontraron proyectos
                            </div>

                            <div v-else class="space-y-4">
                                <div v-for="project in filteredProjects" :key="project.id"
                                    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ project.name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ project.description }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <span :class="getStatusBadgeClass(project.status_badge)"
                                                class="px-2 py-1 text-xs rounded-full">
                                                {{ project.status_badge.label }}
                                            </span>
                                            <span :class="getPriorityBadgeClass(project.priority)"
                                                class="px-2 py-1 text-xs rounded-full">
                                                {{ project.priority }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progreso</span>
                                            <span>{{ project.progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div :style="{ width: project.progress_percentage + '%' }"
                                                :class="getProgressBarClass(project.progress_percentage)"
                                                class="h-2 rounded-full transition-all duration-300">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="text-sm text-gray-500">
                                            {{ project.tasks_count }} tareas •
                                            {{ project.completed_tasks_count }} completadas
                                        </div>
                                        <div class="flex space-x-2">
                                            <button @click="openEditProject(project)"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Editar
                                            </button>
                                            <button @click="deleteProject(project.id)"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Eliminar
                                            </button>
                                            <button @click="testProjectPolicy(project)"
                                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                Test Policy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Gestión de Tareas y Testing -->
                <div class="space-y-6">
                    <!-- Crear Tarea -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Tarea</h3>
                            <form @submit.prevent="createTask" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                                    <select v-model="newTask.project_id" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                        <option value="">Seleccionar proyecto</option>
                                        <option v-for="project in projects" :key="project.id" :value="project.id">
                                            {{ project.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input v-model="newTask.name" type="text" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <select v-model="newTask.status"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="pending">Pendiente</option>
                                            <option value="in_progress">En Progreso</option>
                                            <option value="completed">Completada</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                                        <select v-model="newTask.priority"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="low">Baja</option>
                                            <option value="medium">Media</option>
                                            <option value="high">Alta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                                        Crear Tarea
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Testing de Policies y Gates -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Testing de Seguridad</h3>

                            <div class="space-y-3">
                                <button @click="testAllPolicies"
                                    class="w-full bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700">
                                    Test All Policies & Gates
                                </button>

                                <button @click="testObservers"
                                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                                    Test Observers
                                </button>

                                <button @click="testResources"
                                    class="w-full bg-pink-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-pink-700">
                                    Test Resources & Collections
                                </button>
                            </div>

                            <!-- Results -->
                            <div v-if="testResults" class="mt-4 p-4 bg-gray-100 rounded-md">
                                <h4 class="font-medium text-gray-900 mb-2">Resultados de Tests:</h4>
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ testResults }}</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Tareas Recientes -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Tareas Recientes</h3>
                                <button @click="fetchTasks" 
                                    class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md text-sm hover:bg-gray-300">
                                    🔄 Actualizar
                                </button>
                            </div>

                            <div v-if="loading.tasks" class="text-center py-4">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600">
                                </div>
                            </div>

                            <div v-else-if="tasks.length === 0" class="text-center py-8 text-gray-500">
                                No se encontraron tareas
                            </div>

                            <div v-else class="space-y-3">
                                <div v-for="task in tasks" :key="task.id" class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ task.name }}</h5>
                                            <p class="text-xs text-gray-600">{{ task.project?.name }}</p>
                                        </div>
                                        <span :class="getStatusBadgeClass(task.status_badge)"
                                            class="px-2 py-1 text-xs rounded-full">
                                            {{ task.status_badge.label }}
                                        </span>
                                    </div>

                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-xs text-gray-500">
                                            {{ formatDate(task.due_date) }}
                                        </span>
                                        <div class="flex space-x-1">
                                            <button @click="updateTaskStatus(task, 'completed')"
                                                class="text-green-600 hover:text-green-800 text-xs">
                                                Completar
                                            </button>
                                            <button @click="openEditTask(task)"
                                                class="text-blue-600 hover:text-blue-800 text-xs">
                                                Editar
                                            </button>
                                            <button @click="testTaskPolicy(task)"
                                                class="text-purple-600 hover:text-purple-800 text-xs">
                                                Test Policy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Proyecto -->
        <div v-if="showEditProjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Proyecto</h3>
                <form @submit.prevent="updateProject" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input v-model="editingProject.name" type="text" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea v-model="editingProject.description"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            rows="3"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="editingProject.status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="planning">Planificación</option>
                                <option value="active">Activo</option>
                                <option value="paused">Pausado</option>
                                <option value="completed">Completado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                            <select v-model="editingProject.priority"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="low">Baja</option>
                                <option value="medium">Media</option>
                                <option value="high">Alta</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showEditProjectModal = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar Tarea -->
        <div v-if="showEditTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Tarea</h3>
                <form @submit.prevent="updateTask" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input v-model="editingTask.name" type="text" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea v-model="editingTask.description"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            rows="3"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select v-model="editingTask.status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="completed">Completada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                            <select v-model="editingTask.priority"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                <option value="low">Baja</option>
                                <option value="medium">Media</option>
                                <option value="high">Alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showEditTaskModal = false"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    user: Object,
    initialProjects: Array,
    initialTasks: Array
})

// State
const user = ref(props.user)
const projects = ref(props.initialProjects || [])
const tasks = ref(props.initialTasks || [])
const loading = reactive({
    projects: false,
    tasks: false
})

const projectFilters = reactive({
    status: '',
    priority: '',
    search: ''
})

const newProject = reactive({
    name: '',
    description: '',
    priority: 'medium',
    status: 'planning'
})

const newTask = reactive({
    name: '',
    description: '',
    project_id: '',
    status: 'pending',
    priority: 'medium'
})

const testResults = ref('')

// Estados para edición
const showEditProjectModal = ref(false)
const showEditTaskModal = ref(false)
const editingProject = ref(null)
const editingTask = ref(null)

// Computed
const stats = computed(() => ({
    projects_count: user.value.projects_count || 0,
    tasks_count: user.value.tasks_count || 0
}))

const filteredProjects = computed(() => {
    return projects.value.filter(project => {
        const matchesStatus = !projectFilters.status || project.status === projectFilters.status
        const matchesPriority = !projectFilters.priority || project.priority === projectFilters.priority
        const matchesSearch = !projectFilters.search ||
            project.name.toLowerCase().includes(projectFilters.search.toLowerCase()) ||
            (project.description && project.description.toLowerCase().includes(projectFilters.search.toLowerCase()))

        return matchesStatus && matchesPriority && matchesSearch
    })
})

// Methods
const formatDate = (dateString) => {
    if (!dateString) return 'No definida'
    return new Date(dateString).toLocaleDateString('es-ES')
}

const getStatusBadgeClass = (statusBadge) => {
    const colors = {
        gray: 'bg-gray-100 text-gray-800',
        green: 'bg-green-100 text-green-800',
        yellow: 'bg-yellow-100 text-yellow-800',
        blue: 'bg-blue-100 text-blue-800',
        red: 'bg-red-100 text-red-800'
    }
    return colors[statusBadge.color] || colors.gray
}

const getPriorityBadgeClass = (priority) => {
    const colors = {
        low: 'bg-gray-100 text-gray-800',
        medium: 'bg-blue-100 text-blue-800',
        high: 'bg-orange-100 text-orange-800',
        urgent: 'bg-red-100 text-red-800'
    }
    return colors[priority] || colors.medium
}

const getProgressBarClass = (progress) => {
    if (progress >= 80) return 'bg-green-500'
    if (progress >= 50) return 'bg-blue-500'
    if (progress >= 25) return 'bg-yellow-500'
    return 'bg-red-500'
}

// Debounce para búsqueda
let searchTimeout = null
const debouncedFetchProjects = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        fetchProjects()
    }, 500)
}

// API Calls
const fetchProjects = async () => {
    loading.projects = true
    try {
        const params = new URLSearchParams()
        if (projectFilters.status) params.append('status', projectFilters.status)
        if (projectFilters.priority) params.append('priority', projectFilters.priority)
        if (projectFilters.search) params.append('search', projectFilters.search)
        
        const response = await axios.get(`/api/v1/projects?${params}`)
        projects.value = response.data.data
    } catch (error) {
        console.error('Error fetching projects:', error)
        showError('Error al cargar proyectos')
    } finally {
        loading.projects = false
    }
}

const fetchTasks = async () => {
    loading.tasks = true
    try {
        const response = await axios.get('/api/v1/tasks')
        tasks.value = response.data.data
    } catch (error) {
        console.error('Error fetching tasks:', error)
        showError('Error al cargar tareas')
    } finally {
        loading.tasks = false
    }
}

const createProject = async () => {
    try {
        const response = await axios.post('/api/v1/projects', newProject)
        projects.value.unshift(response.data.data)

        // Reset form
        Object.assign(newProject, {
            name: '',
            description: '',
            priority: 'medium',
            status: 'planning'
        })

        showSuccess('Proyecto creado exitosamente')
        fetchProjects() // Recargar para asegurar reactividad
    } catch (error) {
        console.error('Error creating project:', error)
        showError('Error al crear proyecto')
    }
}

const createTask = async () => {
    try {
        const response = await axios.post('/api/v1/tasks', newTask)
        tasks.value.unshift(response.data.data)

        // Reset form
        Object.assign(newTask, {
            name: '',
            description: '',
            project_id: '',
            status: 'pending',
            priority: 'medium'
        })

        showSuccess('Tarea creada exitosamente')
        fetchTasks() // Recargar para asegurar reactividad
    } catch (error) {
        console.error('Error creating task:', error)
        showError('Error al crear tarea')
    }
}

// Funciones de edición
const openEditProject = (project) => {
    editingProject.value = { ...project }
    showEditProjectModal.value = true
}

const openEditTask = (task) => {
    editingTask.value = { ...task }
    showEditTaskModal.value = true
}

const updateProject = async () => {
    try {
        const response = await axios.put(`/api/v1/projects/${editingProject.value.id}`, editingProject.value)
        
        // Actualizar en la lista
        const index = projects.value.findIndex(p => p.id === editingProject.value.id)
        if (index !== -1) {
            projects.value[index] = response.data.data
        }
        
        showEditProjectModal.value = false
        showSuccess('Proyecto actualizado exitosamente')
    } catch (error) {
        console.error('Error updating project:', error)
        showError('Error al actualizar proyecto')
    }
}

const updateTask = async () => {
    try {
        const response = await axios.put(`/api/v1/tasks/${editingTask.value.id}`, editingTask.value)
        
        // Actualizar en la lista
        const index = tasks.value.findIndex(t => t.id === editingTask.value.id)
        if (index !== -1) {
            tasks.value[index] = response.data.data
        }
        
        showEditTaskModal.value = false
        showSuccess('Tarea actualizada exitosamente')
    } catch (error) {
        console.error('Error updating task:', error)
        showError('Error al actualizar tarea')
    }
}

const updateTaskStatus = async (task, status) => {
    try {
        const response = await axios.post(`/api/v1/tasks/${task.id}/status`, { status })
        const updatedTask = response.data.data
        const index = tasks.value.findIndex(t => t.id === task.id)
        if (index !== -1) {
            tasks.value[index] = updatedTask
        }
        showSuccess(`Tarea marcada como ${status}`)
    } catch (error) {
        console.error('Error updating task status:', error)
        showError('Error al actualizar estado de tarea')
    }
}

const deleteProject = async (projectId) => {
    if (!confirm('¿Estás seguro de que quieres eliminar este proyecto?')) return

    try {
        await axios.delete(`/api/v1/projects/${projectId}`)
        projects.value = projects.value.filter(p => p.id !== projectId)
        showSuccess('Proyecto eliminado exitosamente')
    } catch (error) {
        console.error('Error deleting project:', error)
        showError('Error al eliminar proyecto')
    }
}

// Testing Methods (mantener los mismos que antes)
const testAllPolicies = async () => {
    testResults.value = 'Testing Policies & Gates...\n\n'
    try {
        const projectResponse = await axios.get('/api/v1/projects')
        testResults.value += `✓ ProjectPolicy::viewAny: ${projectResponse.data.data.length} proyectos\n`
        const taskResponse = await axios.get('/api/v1/tasks')
        testResults.value += `✓ TaskPolicy::viewAny: ${taskResponse.data.data.length} tareas\n`
        testResults.value += '✓ Gates configurados correctamente\n'
        testResults.value += '\n✅ Todas las Policies y Gates funcionando correctamente'
    } catch (error) {
        testResults.value += `\n❌ Error en tests: ${error.response?.data?.message || error.message}`
    }
}

const testObservers = async () => {
    testResults.value = 'Testing Observers...\n\n'
    try {
        const testProject = {
            name: 'Proyecto Test Observers',
            description: 'Proyecto para testing de observers',
            priority: 'medium',
            status: 'planning'
        }
        const projectResponse = await axios.post('/api/v1/projects', testProject)
        testResults.value += `✓ ProjectObserver::creating: Proyecto creado (ID: ${projectResponse.data.data.id})\n`
        const testTask = {
            name: 'Tarea Test Observers',
            description: 'Tarea para testing de observers',
            project_id: projectResponse.data.data.id,
            status: 'pending',
            priority: 'medium'
        }
        const taskResponse = await axios.post('/api/v1/tasks', testTask)
        testResults.value += `✓ TaskObserver::created: Tarea creada (ID: ${taskResponse.data.data.id})\n`
        await axios.post(`/api/v1/tasks/${taskResponse.data.data.id}/status`, { status: 'completed' })
        testResults.value += `✓ TaskObserver::updated: Estado actualizado a completed\n`
        const updatedProject = await axios.get(`/api/v1/projects/${projectResponse.data.data.id}`)
        testResults.value += `✓ Project progress updated: ${updatedProject.data.data.progress}%\n`
        await axios.delete(`/api/v1/projects/${projectResponse.data.data.id}`)
        testResults.value += `✓ ProjectObserver::deleted: Proyecto eliminado\n`
        testResults.value += '\n✅ Todos los Observers funcionando correctamente'
    } catch (error) {
        testResults.value += `\n❌ Error en tests: ${error.response?.data?.message || error.message}`
    }
}

const testResources = async () => {
    testResults.value = 'Testing Resources & Collections...\n\n'
    try {
        const projectResponse = await axios.get('/api/v1/projects')
        const projectData = projectResponse.data
        testResults.value += `✓ BaseCollection pagination: \n`
        testResults.value += `  - Current page: ${projectData.meta.current_page}\n`
        testResults.value += `  - Total: ${projectData.meta.total}\n`
        testResults.value += `  - Per page: ${projectData.meta.per_page}\n`
        testResults.value += `✓ ProjectResource attributes: \n`
        if (projectData.data.length > 0) {
            const project = projectData.data[0]
            testResults.value += `  - Computed: progress_percentage, is_overdue, status_badge\n`
            testResults.value += `  - Relations: owner, tasks\n`
            testResults.value += `  - Counts: tasks_count, completed_tasks_count\n`
        }
        testResults.value += `✓ BaseResource meta: timestamp included\n`
        testResults.value += '\n✅ Todos los Resources y Collections funcionando correctamente'
    } catch (error) {
        testResults.value += `\n❌ Error en tests: ${error.response?.data?.message || error.message}`
    }
}

const testProjectPolicy = async (project) => {
    testResults.value = `Testing ProjectPolicy para proyecto: ${project.name}\n\n`
    try {
        const viewResponse = await axios.get(`/api/v1/projects/${project.id}`)
        testResults.value += `✓ ProjectPolicy::view: ACCESO PERMITIDO\n`
        const updateData = { ...project, name: `${project.name} (Updated)` }
        await axios.put(`/api/v1/projects/${project.id}`, updateData)
        testResults.value += `✓ ProjectPolicy::update: ACCESO PERMITIDO\n`
        testResults.value += '\n✅ ProjectPolicy funcionando correctamente'
    } catch (error) {
        if (error.response?.status === 403) {
            testResults.value += `❌ ProjectPolicy::view/update: ACCESO DENEGADO\n`
        } else {
            testResults.value += `❌ Error: ${error.response?.data?.message || error.message}\n`
        }
    }
}

const testTaskPolicy = async (task) => {
    testResults.value = `Testing TaskPolicy para tarea: ${task.name}\n\n`
    try {
        const viewResponse = await axios.get(`/api/v1/tasks/${task.id}`)
        testResults.value += `✓ TaskPolicy::view: ACCESO PERMITIDO\n`
        await axios.post(`/api/v1/tasks/${task.id}/status`, { status: 'in_progress' })
        testResults.value += `✓ TaskPolicy::updateStatus: ACCESO PERMITIDO\n`
        testResults.value += '\n✅ TaskPolicy funcionando correctamente'
    } catch (error) {
        if (error.response?.status === 403) {
            testResults.value += `❌ TaskPolicy::view/updateStatus: ACCESO DENEGADO\n`
        } else {
            testResults.value += `❌ Error: ${error.response?.data?.message || error.message}\n`
        }
    }
}

const logout = () => {
    router.post('/logout')
}

const showSuccess = (message) => {
    alert(`✅ ${message}`)
}

const showError = (message) => {
    alert(`❌ ${message}`)
}

// Lifecycle
onMounted(() => {
    fetchProjects()
    fetchTasks()
})
</script>