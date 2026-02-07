<?php
/**
 * La Clave del Marketing - Controlador de Proyectos
 * 
 * CRUD completo para proyectos de usuario.
 */

require_once APP_PATH . '/Models/Project.php';
require_once APP_PATH . '/Models/ActivityLog.php';

class ProjectController
{
    private Project $projectModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->activityLog = new ActivityLog();
    }

    /**
     * Listar todos los proyectos del usuario
     */
    public function index(): void
    {
        $userId = authId();

        // Obtener filtro de estado si existe
        $status = $_GET['status'] ?? null;

        // Obtener proyectos
        $projects = $this->projectModel->getByUser($userId, $status);

        // Obtener estadísticas
        $stats = $this->projectModel->getStats($userId);

        view('projects.index', [
            'title' => 'Mis Proyectos',
            'projects' => $projects,
            'stats' => $stats,
            'currentStatus' => $status
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): void
    {
        view('projects.create', [
            'title' => 'Nuevo Proyecto'
        ]);
    }

    /**
     * Almacenar nuevo proyecto
     */
    public function store(): void
    {
        // Verificar CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect('/projects/create');
        }

        $userId = authId();

        // Obtener datos
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $niche = trim($_POST['niche'] ?? '');
        $targetAudience = trim($_POST['target_audience'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        // Validación
        $errors = [];
        $old = [
            'name' => $name,
            'description' => $description,
            'niche' => $niche,
            'target_audience' => $targetAudience,
            'status' => $status
        ];

        if (empty($name)) {
            $errors['name'] = 'El nombre del proyecto es obligatorio.';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
        } elseif (strlen($name) > 150) {
            $errors['name'] = 'El nombre no puede exceder 150 caracteres.';
        }

        if (!in_array($status, ['draft', 'active', 'paused', 'completed', 'archived'])) {
            $errors['status'] = 'Estado no válido.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($old);
            redirect('/projects/create');
        }

        // Crear proyecto
        try {
            $projectId = $this->projectModel->createForUser($userId, [
                'name' => $name,
                'description' => $description,
                'niche' => $niche,
                'target_audience' => $targetAudience,
                'status' => $status
            ]);

            // Log de actividad
            $this->activityLog->log(
                ActivityLog::ACTION_PROJECT_CREATE,
                $userId,
                $projectId,
                'project',
                $projectId,
                ['name' => $name]
            );

            setFlash('success', '¡Proyecto creado con éxito!');
            redirect('/projects/' . $projectId);

        } catch (Exception $e) {
            setFlash('error', 'Error al crear el proyecto.');
            setOld($old);
            redirect('/projects/create');
        }
    }

    /**
     * Mostrar un proyecto
     */
    public function show(string $id): void
    {
        $userId = authId();
        $project = $this->projectModel->findByUserAndId($userId, (int) $id);

        if (!$project) {
            setFlash('error', 'Proyecto no encontrado.');
            redirect('/projects');
        }

        // Log de vista
        $this->activityLog->log(
            ActivityLog::ACTION_PROJECT_VIEW,
            $userId,
            (int) $id,
            'project',
            (int) $id
        );

        // Obtener actividad del proyecto
        $activity = $this->activityLog->getByProject((int) $id, 20);

        view('projects.show', [
            'title' => $project['name'],
            'project' => $project,
            'activity' => $activity
        ]);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(string $id): void
    {
        $userId = authId();
        $project = $this->projectModel->findByUserAndId($userId, (int) $id);

        if (!$project) {
            setFlash('error', 'Proyecto no encontrado.');
            redirect('/projects');
        }

        view('projects.edit', [
            'title' => 'Editar: ' . $project['name'],
            'project' => $project
        ]);
    }

    /**
     * Actualizar proyecto
     */
    public function update(string $id): void
    {
        // Verificar CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect('/projects/' . $id . '/edit');
        }

        $userId = authId();
        $projectId = (int) $id;

        // Verificar que el proyecto existe y pertenece al usuario
        $project = $this->projectModel->findByUserAndId($userId, $projectId);
        if (!$project) {
            setFlash('error', 'Proyecto no encontrado.');
            redirect('/projects');
        }

        // Obtener datos
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $niche = trim($_POST['niche'] ?? '');
        $targetAudience = trim($_POST['target_audience'] ?? '');
        $status = $_POST['status'] ?? $project['status'];

        // Validación
        $errors = [];
        $old = [
            'name' => $name,
            'description' => $description,
            'niche' => $niche,
            'target_audience' => $targetAudience,
            'status' => $status
        ];

        if (empty($name)) {
            $errors['name'] = 'El nombre del proyecto es obligatorio.';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        if (!in_array($status, ['draft', 'active', 'paused', 'completed', 'archived'])) {
            $errors['status'] = 'Estado no válido.';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld($old);
            redirect('/projects/' . $id . '/edit');
        }

        // Actualizar proyecto
        try {
            $this->projectModel->updateForUser($userId, $projectId, [
                'name' => $name,
                'description' => $description,
                'niche' => $niche,
                'target_audience' => $targetAudience,
                'status' => $status
            ]);

            // Log de actividad
            $this->activityLog->log(
                ActivityLog::ACTION_PROJECT_UPDATE,
                $userId,
                $projectId,
                'project',
                $projectId,
                ['name' => $name, 'status' => $status]
            );

            setFlash('success', '¡Proyecto actualizado!');
            redirect('/projects/' . $id);

        } catch (Exception $e) {
            setFlash('error', 'Error al actualizar el proyecto.');
            setOld($old);
            redirect('/projects/' . $id . '/edit');
        }
    }

    /**
     * Eliminar proyecto
     */
    public function destroy(string $id): void
    {
        // Verificar CSRF
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect('/projects');
        }

        $userId = authId();
        $projectId = (int) $id;

        // Verificar que el proyecto existe y pertenece al usuario
        $project = $this->projectModel->findByUserAndId($userId, $projectId);
        if (!$project) {
            setFlash('error', 'Proyecto no encontrado.');
            redirect('/projects');
        }

        try {
            // Log antes de eliminar
            $this->activityLog->log(
                ActivityLog::ACTION_PROJECT_DELETE,
                $userId,
                null, // Ya no existirá el proyecto
                'project',
                $projectId,
                ['name' => $project['name']]
            );

            $this->projectModel->deleteForUser($userId, $projectId);

            setFlash('success', 'Proyecto eliminado correctamente.');
            redirect('/projects');

        } catch (Exception $e) {
            setFlash('error', 'Error al eliminar el proyecto.');
            redirect('/projects/' . $id);
        }
    }
}
