@extends('welcome', ['title' => 'Create Person'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <a href="#" id="btnCreate" class="btn btn-sm btn-primary mb-2">Tambah Keluarga</a>
                        <table class="table table-sm table-bordered table-hovered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Anak Dari</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parents as $parent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $parent->name }}</td>
                                        <td>{{ $parent->parent->name ?? '-' }}</td>
                                        <td>{{ $parent->gender }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-id="{{ $parent->id }}" id="editItem">Edit</a>
                                            <form action="{{ route('families.destroy', $parent->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><p class="text-primary">{{ $budiFamilies->name }}</p>
                      <ul>
                        @foreach ($budiFamilies->children as $children)
                        <li><p class="text-{{ $children->gender == 'male' ? 'primary' : 'danger'}}">{{$children->name}} </p>
                          <ul>
                            @foreach ($children->children as $grandchildren)
                                <li><p class="text-{{ $grandchildren->gender == 'male' ? 'primary' : 'danger'}}">{{$grandchildren->name}} </p></li>
                            @endforeach
                          </ul>
                        </li>
                        @endforeach
                      </ul>
                    </li>
                  </ul>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal" id="modal-md" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="itemForm" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="item_id" id="item_id">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Anak Dari</label>
                            <select class="form-control form-control-sm" name="parent_id" id="parent_id">
                                <option selected disabled>Pilih Orang Tua</option>
                                @foreach ($parents as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select class="form-control form-control-sm" name="gender" id="gender">
                                <option selected disabled>Pilih JK</option>
                                <option value="male">Laki-Laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const myModal = document.getElementById('modal-md')
        const btnCreate = document.getElementById('btnCreate')
        const close = myModal.querySelector(".btn-close");
        const nameInput = document.getElementById("name");
        const modalTitle = document.querySelector(".modal-title");
        const itemId = document.getElementById("item_id");
        const itemForm = document.getElementById("itemForm");
        const editItem = document.getElementById('editItem');
        const body = document.querySelector('body');

        btnCreate.addEventListener('click', () => {
            setTimeout(function() {
                nameInput.focus();
            }, 500);
            saveBtn.removeAttribute('disabled');
            saveBtn.innerHTML = "Simpan";
            itemId.value = '';
            itemForm.reset();
            modalTitle.innerHTML = "Tambah Orang";
            myModal.style.display = "block";
        })

        close.addEventListener('click', () => {
            myModal.style.display = "none";
        })

        body.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'editItem') {
                const item_id = e.target.getAttribute('data-id');
                fetch("{{ route('families.index') }}" + '/' + item_id + '/edit', {
                        method: 'GET',
                    })
                    .then(response => response.json())
                    .then(data => {
                        myModal.style.display = "block";
                        setTimeout(function() {
                            document.getElementById('name').focus();
                        }, 500);
                        document.querySelector('.modal-title').innerHTML = "Edit Orang";
                        document.getElementById('saveBtn').removeAttribute('disabled');
                        document.getElementById('saveBtn').innerHTML = "Simpan";
                        document.getElementById('item_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('parent_id').value = data.parent_id;
                        document.getElementById('gender').value = data.gender;
                    })
                    .catch(error => console.log(error));
            }
        });

        saveBtn.addEventListener("click", function(e) {
            e.preventDefault();
            saveBtn.setAttribute('disabled', 'disabled');
            saveBtn.innerHTML = 'Simpan ...';
            const formData = new FormData(itemForm);
            fetch("{{ route('families.store') }}", {
                    method: 'POST',
                    // headers: {
                    //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    // },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    itemForm.reset();
                    myModal.style.display = "none";
                    window.location.reload();
                })
                .catch(error => {
                    saveBtn.removeAttribute('disabled');
                    saveBtn.innerHTML = "Simpan";
                    alert(error)
                })
        })
    </script>
@endpush
