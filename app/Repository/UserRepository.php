<?php

namespace App\Repository;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRepository
{


    /**
     * Get base table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getBaseTable()
    {
        $builder = DB::table('users')
                     ->select(
                        'users.*',
                        'roles.title as role'
                     )
                     ->join('roles', 'users.role_id', '=', 'roles.id');

        return $builder;
    }

    public function getDataTable(Request $request)
    {

        $limit = $request->length;
        $offset = $request->start;

        $order = $request->order;
        $columns = $request->columns;

        $column_idx = $order[0]['column'];
        $column = $columns[$column_idx]['data'];

        $column = $this->mapColumn($column);
        
        $orderDirection = $order[0]['dir'];

        $total = $this->getBaseTable()
                      ->where(function($query) use ($request) {
                        $search = $request->search['value'];
                        if (!empty($search)) {
                            return $query->where('users.name', 'like', '%' . $search . '%')
                                  ->orWhere('roles.title', 'like', '%' . $search . '%')
                                  ->orWhere('users.status', 'like', '%' . $search . '%');
                        }
                        $query->whereNull('users.deleted_at');
                      })
                      ->count();
        $users = $this->getBaseTable()
                        ->where(function($query) use ($request) {
                            $search = $request->search['value'];
                            if (!empty($search)) {
                                return $query->where('users.name', 'like', '%' . $search . '%')
                                        ->orWhere('roles.title', 'like', '%' . $search . '%')
                                        ->orWhere('users.status', 'like', '%' . $search . '%');
                            }
                            
                            $query->whereNull('users.deleted_at');
                        })
                        ->limit($limit)
                        ->offset($offset)
                        ->orderBy($column, $orderDirection)
                        ->get();

        $users = $users->toArray();

        $totalRecords = $total;
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $users,
        ];

        return $data;
    }


    /**
     * Map datatable column to database
     *
     * @param string $column
     * 
     * @return string
     */
    protected function mapColumn(string $column)
    {
        $columns = [
            'name' => 'users.name',
            'status' => 'users.status',
            'role' => 'roles.title',
        ];

        return $columns[$column];
    }
}