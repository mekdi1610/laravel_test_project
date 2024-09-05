<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
      // GET /vehicles to list all vehicles
      public function index()
      {
          $vehicles = Vehicle::paginate(10);
          return response()->json($vehicles);
      }

      // POST /vehicles to add a new vehicle
      public function store(Request $request)
      {
          $request->validate([
              'make' => 'required|string',
              'model' => 'required|string',
              'year' => 'required|integer|min:1900',
              'license_plate' => 'required|string|unique:vehicles',
          ]);

          $vehicle = Vehicle::create($request->all());
          return response()->json($vehicle, 201);
      }

      // GET /vehicles/{id} to show details of a specific vehicle
      public function show($id)
      {
          $vehicle = Vehicle::findOrFail($id);
          return response()->json($vehicle);
      }

      // PUT /vehicles/{id} to update a vehicle
      public function update(Request $request, $id)
      {
          $vehicle = Vehicle::findOrFail($id);
          $request->validate([
              'make' => 'string',
              'model' => 'string',
              'year' => 'integer|min:1900',
              'license_plate' => 'string|unique:vehicles,license_plate,'.$id,
          ]);

          $vehicle->update($request->all());
          return response()->json($vehicle);
      }

      // DELETE /vehicles/{id} to delete a vehicle
      public function destroy($id)
      {
          $vehicle = Vehicle::findOrFail($id);
          $vehicle->delete();
          return response()->json(['message' => 'Deleted Successfully'], 202);
      }


}
