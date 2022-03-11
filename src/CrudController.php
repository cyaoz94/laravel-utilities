<?php

namespace Cyaoz94\LaravelUtilities;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Cyaoz94\LaravelUtilities\Filters\Filterable;

class CrudController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $modelClass;
    protected $resourceClass;
    protected $filterClass;
    protected $filter;

    public function __construct(Request $request)
    {
        // if the inherited class has a filter class
        // create a new instance of that filter class
        $this->filter = $this->filterClass
            ? new $this->filterClass($request)
            : null;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->isClassFilterable() && !!$this->filter
            ? $this->modelClass::filter($this->filter)
            : $this->modelClass::query();

        $perPage = $request->has('per_page') ? intval($request->input('per_page')) : 10;
        $items = $query->paginate($perPage);

        return $this->commonOffsetPaginationJsonResponse($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $validationRules
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, array $validationRules = [])
    {
        $validatedData = $request->validate($validationRules);

        $this->modelClass::create($validatedData);

        return $this->commonJsonResponse([], 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = $this->modelClass::findOrFail($id);

        return $this->commonJsonResponse($this->resourceClass ? new $this->resourceClass($item) : $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @param array $validationRules
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, array $validationRules = [])
    {
        $validatedData = $request->validate($validationRules);

        $item = $this->modelClass::findOrFail($id);
        $item->fill($validatedData);
        $item->save();

        return $this->commonJsonResponse([], 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $item = $this->modelClass::findOrFail($id);
        $item->delete();

        return $this->commonJsonResponse([], 'Deleted Successfully');
    }

    /**
     * checks if a class uses the Filterable trait
     * @return bool
     */
    private function isClassFilterable()
    {
        return in_array(Filterable::class, class_uses($this->modelClass));
    }

    protected function commonJsonResponse($data, $message = null, $code = 0)
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ]);
    }

    protected function commonOffsetPaginationJsonResponse($data, $jsonResource = null, $message = null, $code = 0)
    {
        return response()->json([
            'code' => $code,
            'data' => $jsonResource ? $jsonResource::collection($data) : $data->items(),
            'message' => $message,
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total' => $data->count(),
                'per_page' => $data->perPage(),
                'last_page' => $data->lastPage(),
            ],
        ]);
    }
}
