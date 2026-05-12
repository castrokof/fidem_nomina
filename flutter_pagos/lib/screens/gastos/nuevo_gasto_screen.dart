import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/gasto.dart';
import '../../providers/gastos_provider.dart';
import '../../theme/app_theme.dart';
import '../../widgets/ios_field.dart';

class NuevoGastoScreen extends StatefulWidget {
  final Gasto? gasto;
  const NuevoGastoScreen({super.key, this.gasto});

  @override
  State<NuevoGastoScreen> createState() => _NuevoGastoScreenState();
}

class _NuevoGastoScreenState extends State<NuevoGastoScreen> {
  final _descripcion = TextEditingController();
  final _monto       = TextEditingController();
  final _notas       = TextEditingController();
  String? _categoria;
  DateTime _fecha = DateTime.now();
  bool _guardando = false;

  @override
  void initState() {
    super.initState();
    final g = widget.gasto;
    if (g != null) {
      _descripcion.text = g.descripcion;
      _monto.text       = g.monto.toStringAsFixed(0);
      _notas.text       = g.notas ?? '';
      _categoria        = g.categoria;
      _fecha            = g.fecha;
    }
  }

  @override
  void dispose() {
    _descripcion.dispose();
    _monto.dispose();
    _notas.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final prov = context.watch<GastosProvider>();
    final esEdicion = widget.gasto != null;

    return Scaffold(
      backgroundColor: AppColors.bg,
      appBar: AppBar(
        title: Text(esEdicion ? 'Editar Gasto' : 'Nuevo Gasto'),
        actions: [
          TextButton(
            onPressed: _guardando ? null : () => _guardar(prov),
            child: _guardando
                ? const SizedBox(
                    width: 16, height: 16,
                    child: CircularProgressIndicator(strokeWidth: 2))
                : const Text('Guardar',
                    style: TextStyle(
                        color: AppColors.blue,
                        fontWeight: FontWeight.w700)),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          IosSection(label: 'Detalle', children: [
            IosField(
              icon: Icons.receipt_outlined, iconColor: AppColors.blue,
              iconBg: const Color(0x1F007AFF), label: 'Descripción *',
              child: IosTextInput(
                controller: _descripcion,
                placeholder: 'Almuerzo, taxi, papelería…',
              ),
            ),
            IosField(
              icon: Icons.attach_money, iconColor: AppColors.green,
              iconBg: const Color(0x1F34C759), label: 'Monto *',
              child: IosTextInput(
                controller: _monto,
                placeholder: '0',
                keyboardType: TextInputType.number,
              ),
            ),
            IosField(
              icon: Icons.label_outline,
              iconColor: const Color(0xFF5856D6),
              iconBg: const Color(0x1F5856D6),
              label: 'Categoría',
              child: Row(children: [
                Expanded(
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      value: _categoria,
                      isExpanded: true,
                      hint: const Text('Sin categoría',
                          style: TextStyle(
                              color: AppColors.gray2, fontSize: 14)),
                      style: const TextStyle(
                          fontSize: 14, color: AppColors.label),
                      items: [
                        const DropdownMenuItem(
                            value: null, child: Text('Sin categoría')),
                        ...prov.categorias.map((c) =>
                            DropdownMenuItem(value: c, child: Text(c))),
                      ],
                      onChanged: (v) => setState(() => _categoria = v),
                    ),
                  ),
                ),
                GestureDetector(
                  onTap: () => _nuevaCategoria(context, prov),
                  child: Container(
                    width: 26, height: 26,
                    decoration: BoxDecoration(
                      color: const Color(0x1F007AFF),
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: const Icon(Icons.add,
                        color: AppColors.blue, size: 14),
                  ),
                ),
              ]),
            ),
          ]),

          IosSection(label: 'Fecha', children: [
            IosField(
              icon: Icons.calendar_today, iconColor: AppColors.red,
              iconBg: const Color(0x1FFF3B30), label: 'Fecha del gasto',
              child: GestureDetector(
                onTap: _seleccionarFecha,
                child: Text(
                  '${_fecha.day.toString().padLeft(2, '0')}/'
                  '${_fecha.month.toString().padLeft(2, '0')}/'
                  '${_fecha.year}',
                  style: const TextStyle(
                      fontSize: 14, color: AppColors.label),
                ),
              ),
            ),
          ]),

          IosSection(label: 'Notas', children: [
            IosField(
              icon: Icons.notes, iconColor: AppColors.gray,
              iconBg: const Color(0x1F8E8E93), label: 'Notas',
              child: IosTextInput(
                controller: _notas,
                placeholder: 'Observaciones opcionales…',
                maxLines: 2,
              ),
            ),
          ]),

          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Future<void> _seleccionarFecha() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _fecha,
      firstDate: DateTime(DateTime.now().year - 2),
      lastDate: DateTime.now(),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: const ColorScheme.light(primary: AppColors.blue),
        ),
        child: child!,
      ),
    );
    if (picked != null) setState(() => _fecha = picked);
  }

  Future<void> _guardar(GastosProvider prov) async {
    final desc  = _descripcion.text.trim();
    final monto = double.tryParse(_monto.text.trim());
    if (desc.isEmpty || monto == null || monto <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content: Text('Descripción y monto son obligatorios.')));
      return;
    }
    setState(() => _guardando = true);
    final gasto = Gasto(
      id: widget.gasto?.id,
      descripcion: desc,
      monto: monto,
      categoria: _categoria,
      fecha: _fecha,
      notas: _notas.text.trim().isEmpty ? null : _notas.text.trim(),
    );
    await prov.guardarGasto(gasto);
    if (mounted) {
      setState(() => _guardando = false);
      Navigator.pop(context);
    }
  }

  void _nuevaCategoria(BuildContext ctx, GastosProvider prov) {
    final ctrl = TextEditingController();
    showDialog(
      context: ctx,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14)),
        title: const Text('Nueva categoría'),
        content: TextField(
          controller: ctrl,
          autofocus: true,
          decoration: const InputDecoration(
              hintText: 'Nombre de la categoría'),
        ),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancelar')),
          TextButton(
            onPressed: () async {
              final nombre = ctrl.text.trim();
              if (nombre.isEmpty) return;
              await prov.agregarCategoria(nombre);
              setState(() => _categoria = nombre);
              if (ctx.mounted) Navigator.pop(ctx);
            },
            child: const Text('Agregar',
                style: TextStyle(
                    color: AppColors.blue,
                    fontWeight: FontWeight.w700)),
          ),
        ],
      ),
    );
  }
}
