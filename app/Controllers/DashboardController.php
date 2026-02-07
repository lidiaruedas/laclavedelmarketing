<?php
/**
 * La Clave del Marketing - Controlador del Dashboard
 * 
 * Página principal después del login.
 */

require_once APP_PATH . '/Models/Project.php';
require_once APP_PATH . '/Models/ActivityLog.php';

class DashboardController
{
    private Project $projectModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->activityLog = new ActivityLog();
    }

    /**
     * Mostrar dashboard principal
     */
    public function index(): void
    {
        $userId = authId();

        // Obtener estadísticas de proyectos
        $projectStats = $this->projectModel->getStats($userId);

        // Obtener proyectos recientes
        $recentProjects = $this->projectModel->getByUser($userId);
        $recentProjects = array_slice($recentProjects, 0, 5); // Solo los 5 más recientes

        // Obtener actividad reciente
        $recentActivity = $this->activityLog->getByUser($userId, 10);

        view('dashboard.index', [
            'title' => 'Dashboard',
            'projectStats' => $projectStats,
            'recentProjects' => $recentProjects,
            'recentActivity' => $recentActivity
        ]);
    }
}
