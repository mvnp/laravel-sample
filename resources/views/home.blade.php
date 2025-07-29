<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consulta de Estabelecimentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-search"></i> 
                            Consulta de Estabelecimentos
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Search Form -->
                        <form method="POST" action="{{ route('api/estabelecimentos') }}" id="searchForm">
                            @csrf
                            
                            <!-- Error Messages -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-3">
                                <!-- CNPJ -->
                                <div class="col-md-3">
                                    <label for="cnpj" class="form-label">CNPJ Básico (8 dígitos)</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                           value="{{ old('cnpj', $searchData['cnpj'] ?? '') }}"
                                           placeholder="12345678" maxlength="8">
                                </div>

                                <!-- Situação Cadastral -->
                                <div class="col-md-3">
                                    <label for="situacao" class="form-label">Situação Cadastral</label>
                                    <select class="form-select" id="situacao" name="situacao">
                                        <option value="">Todas</option>
                                        <option value="01" {{ (old('situacao', $searchData['situacao'] ?? '') == '01') ? 'selected' : '' }}>Nula</option>
                                        <option value="02" {{ (old('situacao', $searchData['situacao'] ?? '') == '02') ? 'selected' : '' }}>Ativa</option>
                                        <option value="03" {{ (old('situacao', $searchData['situacao'] ?? '') == '03') ? 'selected' : '' }}>Suspensa</option>
                                        <option value="04" {{ (old('situacao', $searchData['situacao'] ?? '') == '04') ? 'selected' : '' }}>Inapta</option>
                                        <option value="08" {{ (old('situacao', $searchData['situacao'] ?? '') == '08') ? 'selected' : '' }}>Baixada</option>
                                    </select>
                                </div>

                                <!-- Nome Fantasia -->
                                <div class="col-md-6">
                                    <label for="fantasia" class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" id="fantasia" name="fantasia" 
                                           value="{{ old('fantasia', $searchData['fantasia'] ?? '') }}"
                                           placeholder="Digite parte do nome fantasia">
                                </div>

                                <!-- Estado -->
                                <div class="col-md-3">
                                    <label for="uf" class="form-label">Estado</label>
                                    <select class="form-select" id="uf" name="uf">
                                        <option value="">Selecione...</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->codigo }}" 
                                                {{ (old('uf', $searchData['uf'] ?? '') == $estado->codigo) ? 'selected' : '' }}>
                                                {{ $estado->nome }} ({{ $estado->codigo }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Município -->
                                <div class="col-md-3">
                                    <label for="municipio" class="form-label">Município</label>
                                    <select class="form-select" id="municipio" name="municipio" disabled>
                                        <option value="">Selecione um estado primeiro...</option>
                                        @if(isset($municipios) && $municipios->count() > 0)
                                            @foreach($municipios as $municipio)
                                                <option value="{{ $municipio->codigo }}"
                                                    {{ (old('municipio', $searchData['municipio'] ?? '') == $municipio->codigo) ? 'selected' : '' }}>
                                                    {{ $municipio->nome }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- CEP -->
                                <div class="col-md-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" 
                                           value="{{ old('cep', $searchData['cep'] ?? '') }}"
                                           placeholder="12345-678">
                                </div>

                                <!-- CNAE Principal -->
                                <div class="col-md-3">
                                    <label for="cnae_principal" class="form-label">CNAE Principal</label>
                                    <select class="form-select select2" id="cnae_principal" name="cnae_principal">
                                        <option value="">Selecione...</option>
                                        @foreach($cnaes as $cnae)
                                            <option value="{{ $cnae->codigo }}"
                                                {{ (old('cnae_principal', $searchData['cnae_principal'] ?? '') == $cnae->codigo) ? 'selected' : '' }}>
                                                {{ $cnae->codigo }} - {{ $cnae->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- CNAE Secundária -->
                                <div class="col-md-6">
                                    <label for="cnae_secundaria" class="form-label">CNAE Secundária</label>
                                    <select class="form-select select2" id="cnae_secundaria" name="cnae_secundaria">
                                        <option value="">Selecione...</option>
                                        @foreach($cnaes as $cnae)
                                            <option value="{{ $cnae->codigo }}"
                                                {{ (old('cnae_secundaria', $searchData['cnae_secundaria'] ?? '') == $cnae->codigo) ? 'selected' : '' }}>
                                                {{ $cnae->codigo }} - {{ $cnae->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Capital Social -->
                                <div class="col-md-6">
                                    <label for="capital_entre" class="form-label">Capital Social (Entre)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="capital_inicial" 
                                               placeholder="Valor mínimo" step="0.01">
                                        <span class="input-group-text">até</span>
                                        <input type="number" class="form-control" id="capital_final" 
                                               placeholder="Valor máximo" step="0.01">
                                        <input type="hidden" id="capital_entre" name="capital_entre" 
                                               value="{{ old('capital_entre', $searchData['capital_entre'] ?? '') }}">
                                    </div>
                                </div>

                                <!-- Limite de Resultados -->
                                <div class="col-md-3">
                                    <label for="limit" class="form-label">Limite de Resultados</label>
                                    <select class="form-select" id="limit" name="limit">
                                        <option value="50" {{ (old('limit', $searchData['limit'] ?? '50') == '50') ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ (old('limit', $searchData['limit'] ?? '') == '100') ? 'selected' : '' }}>100</option>
                                        <option value="200" {{ (old('limit', $searchData['limit'] ?? '') == '200') ? 'selected' : '' }}>200</option>
                                        <option value="500" {{ (old('limit', $searchData['limit'] ?? '') == '500') ? 'selected' : '' }}>500</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Pesquisar
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearForm">
                                            <i class="bi bi-arrow-clockwise"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Table -->
                @if(isset($results) && $results->count() > 0)
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-table"></i> 
                                Resultados Encontrados ({{ number_format($results->count()) }})
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>CNPJ</th>
                                            <th>Razão Social</th>
                                            <th>Nome Fantasia</th>
                                            <th>Situação</th>
                                            <th>CNAE Principal</th>
                                            <th>UF</th>
                                            <th>Município</th>
                                            <th>Capital Social</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $estabelecimento)
                                            <tr>
                                                <td>
                                                    <code>{{ $estabelecimento->cnpj_completo ?? ($estabelecimento->cnpj_basico . 'XXXX' . $estabelecimento->cnpj_ordem . $estabelecimento->cnpj_dv) }}</code>
                                                </td>
                                                <td>{{ $estabelecimento->empresa->razao_social ?? 'N/A' }}</td>
                                                <td>{{ $estabelecimento->nome_fantasia ?: '-' }}</td>
                                                <td>
                                                    @php
                                                        $situacoes = [
                                                            '01' => ['Nula', 'danger'],
                                                            '02' => ['Ativa', 'success'], 
                                                            '03' => ['Suspensa', 'warning'],
                                                            '04' => ['Inapta', 'secondary'],
                                                            '08' => ['Baixada', 'dark']
                                                        ];
                                                        $situacao = $situacoes[$estabelecimento->situacao_cadastral] ?? ['Desconhecida', 'light'];
                                                    @endphp
                                                    <span class="badge bg-{{ $situacao[1] }}">{{ $situacao[0] }}</span>
                                                </td>
                                                <td>{{ $estabelecimento->cnae_principal }}</td>
                                                <td>{{ $estabelecimento->uf }}</td>
                                                <td>{{ $estabelecimento->municipio }}</td>
                                                <td>
                                                    @if($estabelecimento->empresa && $estabelecimento->empresa->capital_social)
                                                        R$ {{ number_format($estabelecimento->empresa->capital_social, 2, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif(isset($results))
                    <div class="card shadow-sm mt-4">
                        <div class="card-body text-center">
                            <i class="bi bi-search display-1 text-muted"></i>
                            <h5 class="mt-3">Nenhum resultado encontrado</h5>
                            <p class="text-muted">Tente ajustar os filtros de pesquisa</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Selecione...',
                allowClear: true
            });

            // Handle UF change to load municipalities
            $('#uf').change(function() {
                const uf = $(this).val();
                const municipioSelect = $('#municipio');
                
                if (uf) {
                    $.ajax({
                        url: '{{ route("home.municipios") }}',
                        method: 'GET',
                        data: { uf: uf },
                        success: function(data) {
                            municipioSelect.empty().append('<option value="">Selecione...</option>');
                            $.each(data, function(i, municipio) {
                                municipioSelect.append(`<option value="${municipio.codigo}">${municipio.nome}</option>`);
                            });
                            municipioSelect.prop('disabled', false);
                        },
                        error: function() {
                            alert('Erro ao carregar municípios');
                        }
                    });
                } else {
                    municipioSelect.empty()
                        .append('<option value="">Selecione um estado primeiro...</option>')
                        .prop('disabled', true);
                }
            });

            // Handle capital range inputs
            $('#capital_inicial, #capital_final').on('input', function() {
                const inicial = $('#capital_inicial').val();
                const final = $('#capital_final').val();
                
                if (inicial && final) {
                    $('#capital_entre').val(`${inicial},${final}`);
                } else {
                    $('#capital_entre').val('');
                }
            });

            // Set capital range values on page load
            const capitalEntre = $('#capital_entre').val();
            if (capitalEntre) {
                const [inicial, final] = capitalEntre.split(',');
                $('#capital_inicial').val(inicial);
                $('#capital_final').val(final);
            }

            // Clear form
            $('#clearForm').click(function() {
                $('#searchForm')[0].reset();
                $('.select2').val(null).trigger('change');
                $('#municipio').empty()
                    .append('<option value="">Selecione um estado primeiro...</option>')
                    .prop('disabled', true);
                $('#capital_entre').val('');
            });

            // Format CNPJ input
            $('#cnpj').on('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 8);
            });

            // Format CEP input
            $('#cep').on('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length >= 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
                this.value = value;
            });
        });
    </script>
</body>
</html>