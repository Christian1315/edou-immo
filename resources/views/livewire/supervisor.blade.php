<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count(supervisors())}} </strong> </h4>

                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom/Prénom</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Date de création</th>
                            <th class="text-center">Gestionnaires de Compte</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(supervisors() as $supervisor)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$supervisor["name"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$supervisor["email"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$supervisor["phone"]}}</span></td>
                            <td class="text-center text-red"> <strong class=" bg-light text-red"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($supervisor->created_at)->locale('fr')->isoFormat('D MMMM YYYY') }} </strong> </th>
                            <td class="text-center text-red">
                                <button class="btn btn-sm btn-light" onclick="showAgentModal({{$supervisor->id}})" data-bs-toggle="modal" data-bs-target="#supervisorAgentModal"><i class="bi bi-list-check"></i></button>
                            </td>
                            <td class="text-center">
                                <button class="btn text-dark btn-sm bg-light mx-1" onclick="affectToAgent({{$supervisor->id}})" data-bs-toggle="modal" data-bs-target="#affectationModal"><i class="bi bi-link"></i>Affecter à un Gestionnaire de compte</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal des agents -->
    <div class="modal fade" id="supervisorAgentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <p class="fs-5 text-center" id="exampleModalLabel">Superviseur: <strong class="text-red supervisor_name"> </strong> </p>
                </div>
                <div class="modal-body" id="agentsBody">
                    <!-- gerer ave du JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal affectation de superviseur à un agent -->
    <div class="modal fade" id="affectationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <p class="fs-5 text-center" id="exampleModalLabel">Superviseur: <strong class="text-red supervisor_name"> </strong> </p>
                </div>
                <div class="modal-body">
                    <form id="affectationModalForm" method="post">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <label for="">Choisissez un agent</label>
                        <select id="agent-select" required name="agent" class="form-select mb-3 form-control agency-modal-select2">
                            @foreach($compteAgents as $agent)
                            <option value="{{$agent->id}}">{{$agent->name}}</option>
                            @endforeach
                        </select>
                        <div class="modal-footer justify-center">
                            <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-link"></i> Affecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // show supervisor agents
        function showAgentModal(id) {
            axios.get(`/users/${id}/retrieve`).then((response) => {
                let data = response.data;

                $('.supervisor_name').html(data.name);
                $("#agentsBody").empty();

                let content = ''

                if (data.account_agents.length > 0) {
                    let rows = ''
                    data.account_agents.forEach(agent => {
                        rows += `
                            <li class="list-group-item">${agent.name}</li>
                        `
                    });

                    content = `
                        <ul class="list-group text-center">
                            ${rows}
                        </ul>
                    `
                } else {
                    content = `<p class="text-center">Aucun agent associé</p>`
                }

                $("#agentsBody").append(content)
                $('#supervisorAgentModal').modal('show');
            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })
        }

        // affect supervisor to agents
        function affectToAgent(id) {
            axios.get(`/users/${id}/retrieve`).then((response) => {
                let data = response.data;

                $('.supervisor_name').html(data.name);
                $('#user_id').val(id);

                $('#affectationModalForm').attr("action", `attach-supervisor-to-agent_account/${id}`)
                $('#affectationModal').modal('show');
            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })
        }
    </script>
</div>