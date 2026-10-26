<?php

namespace App\Http\Controllers;

use App\Services\CctvService;
use Illuminate\Http\Request;

class CctvController extends Controller
{
    protected CctvService $cctvService;

    public function __construct(CctvService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Display CCTV camera monitoring dashboard
     */
    public function index(Request $request)
    {
        $fresh = $request->has('refresh');
        $cameras = $this->cctvService->getCameras($fresh);
        
        $selectedGroupId = $request->input('group');
        $selectedStatus  = $request->input('status');
        $quality         = $request->input('quality', 'sub');
        $viewMode        = $request->input('view', 'grid'); // 'grid' | 'single'

        // Extract unique groups and counters
        $groups = [];
        $onlineCount = 0;
        $offlineCount = 0;

        foreach ($cameras as $cam) {
            if (($cam['status'] ?? '') === 'online') {
                $onlineCount++;
            } else {
                $offlineCount++;
            }

            if (!empty($cam['groups'])) {
                foreach ($cam['groups'] as $grp) {
                    $groups[$grp['id']] = $grp['name'];
                }
            }
        }

        // Filter cameras
        $filteredCameras = array_filter($cameras, function ($cam) use ($selectedGroupId, $selectedStatus) {
            if ($selectedStatus && ($cam['status'] ?? '') !== $selectedStatus) {
                return false;
            }
            if ($selectedGroupId) {
                $camGroupIds = array_column($cam['groups'] ?? [], 'id');
                if (!in_array($selectedGroupId, $camGroupIds)) {
                    return false;
                }
            }
            return true;
        });

        // Pre-fetch embed URLs for filtered cameras
        foreach ($filteredCameras as &$cam) {
            $cam['embed_url'] = $this->cctvService->getEmbedUrl($cam['id'], $quality);
        }
        unset($cam);

        $filteredCameras = array_values($filteredCameras);

        // Find active camera for single view
        $activeCameraId = $request->input('camera', (!empty($filteredCameras) ? $filteredCameras[0]['id'] : null));
        $activeCamera = null;
        if ($activeCameraId) {
            foreach ($cameras as $cam) {
                if ($cam['id'] == $activeCameraId) {
                    $activeCamera = $cam;
                    $activeCamera['embed_url'] = $this->cctvService->getEmbedUrl($cam['id'], $quality);
                    break;
                }
            }
        }

        return view('admin.cctv.index', [
            'cameras'         => $filteredCameras,
            'allCameras'      => $cameras,
            'groups'          => $groups,
            'selectedGroupId' => $selectedGroupId,
            'selectedStatus'  => $selectedStatus,
            'viewMode'        => $viewMode,
            'quality'         => $quality,
            'activeCamera'    => $activeCamera,
            'onlineCount'     => $onlineCount,
            'offlineCount'    => $offlineCount,
            'totalCount'      => count($cameras),
            'cctvBaseUrl'     => $this->cctvService->getBaseUrl(),
        ]);
    }

    /**
     * AJAX endpoint to get embed token and URL for a specific camera
     */
    public function stream(Request $request, $id)
    {
        $quality = $request->input('quality', 'sub');
        $camera = $this->cctvService->getCamera($id);

        if (!$camera) {
            return response()->json([
                'success' => false,
                'message' => 'Kamera topilmadi',
            ], 404);
        }

        $embed = $this->cctvService->generateEmbed($id, 72, $quality);

        return response()->json([
            'success'   => true,
            'camera'    => $camera,
            'embed'     => $embed,
            'embed_url' => $embed['embed_url'] ?? null,
        ]);
    }

    /**
     * Fullscreen Video Wall for security surveillance / monitoring displays
     */
    public function wall(Request $request)
    {
        $quality = $request->input('quality', 'sub');
        $cameras = $this->cctvService->getCameras($request->has('refresh'));

        foreach ($cameras as &$cam) {
            $cam['embed_url'] = $this->cctvService->getEmbedUrl($cam['id'], $quality);
        }
        unset($cam);

        return view('admin.cctv.wall', [
            'cameras'     => $cameras,
            'quality'     => $quality,
            'cctvBaseUrl' => $this->cctvService->getBaseUrl(),
        ]);
    }

    /**
     * Dedicated PTZ Camera Control Center
     */
    public function ptz(Request $request)
    {
        $quality = $request->input('quality', 'sub');
        $cameras = $this->cctvService->getCameras();

        // Cameras that support PTZ (specifically Camera 1, Camera 10, Camera 4, or any with is_ptz)
        $ptzCameras = array_filter($cameras, function ($c) {
            return !empty($c['is_ptz']) || in_array($c['id'], [1, 4, 10]);
        });

        if (empty($ptzCameras)) {
            $ptzCameras = $cameras;
        }

        $activeCameraId = $request->input('camera', (!empty($ptzCameras) ? reset($ptzCameras)['id'] : 1));
        $activeCamera = null;
        foreach ($cameras as $c) {
            if ($c['id'] == $activeCameraId) {
                $activeCamera = $c;
                break;
            }
        }

        if ($activeCamera) {
            $activeCamera['embed_url'] = $this->cctvService->getEmbedUrl($activeCamera['id'], $quality);
        }

        return view('admin.cctv.ptz', [
            'cameras'      => array_values($cameras),
            'ptzCameras'   => array_values($ptzCameras),
            'activeCamera' => $activeCamera,
            'quality'      => $quality,
            'cctvBaseUrl'  => $this->cctvService->getBaseUrl(),
        ]);
    }

    /**
     * Proxy PTZ API calls to CCTV server safely
     */
    public function ptzAction(Request $request, $id, $action, $subId = null)
    {
        $method = $request->method();
        $data = $request->all();
        $endpoint = $action . ($subId ? "/{$subId}" : "");

        $res = $this->cctvService->ptzRequest($id, $endpoint, $method, $data);

        return response()->json($res['data'] ?? ['success' => $res['success']], $res['status'] ?? 200);
    }
}
