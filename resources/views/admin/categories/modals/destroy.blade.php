<div class="modal fade" id="destroy-category-{{$category->id}}">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header border-danger">
        <h3 class="h5 modal-title text-danger">
          <i class="fa-solid fa-trash-can"></i> Delete Category
        </h3>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <span class="fw-bold">{{$category->name}}</span> category?
        <p class="text-secondary small mt-2">This action will affect all the posts under this category. Posts without a category will fall under Uncategorized.</p>
        <div class="mt-3 d-flex justify-content-end">
          <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-dismiss="modal">Cancel</button>
          <form action="{{route('admin.categories.destroy', $category->id)}}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>