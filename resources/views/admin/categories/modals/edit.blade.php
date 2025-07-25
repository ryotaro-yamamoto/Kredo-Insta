<div class="modal fade" id="edit-category-{{$category->id}}">
  <div class="modal-dialog">
    <div class="modal-content border-warning">
      <div class="modal-header border-warning">
        <h3 class="h5 modal-title">
          <i class="fa-regular fa-pen-to-square"></i> Edit Category
        </h3>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.categories.update', $category->id)}}" method="post">
          @csrf
          @method('PATCH')
          <div class="mb-3">
            <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}" required>
            @error('name')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
            <div class="mt-3 d-flex justify-content-end">
              <button type="button" class="btn btn-outline-warning btn-sm me-2" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-warning btn-sm">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>