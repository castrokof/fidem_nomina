import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/factura.dart';
import '../../providers/pagos_provider.dart';
import '../../theme/app_theme.dart';
import '../../widgets/ios_field.dart';

class NuevaFacturaScreen extends StatefulWidget {
  final Factura? factura;
  const NuevaFacturaScreen({super.key, this.factura});

  @override
  State<NuevaFacturaScreen> createState() => _NuevaFacturaScreenState();
}

class _NuevaFacturaScreenState extends State<NuevaFacturaScreen> {
  final _nombre     = TextEditingController();
  final _referencia = TextEditingController();
  final _sede       = TextEditingController();
  final _descripcion= TextEditingController();
  final _dia        = TextEditingController();
  final _monto      = TextEditingController();
  final _correos    = TextEditingController();
  final _aviso      = TextEditingController(text: '3');
  String? _categoria;
  bool _guardando = false;

  @override
  void initState() {
    super.initState();
    final f = widget.factura;
    if (f != null) {
      _nombre.text      = f.nombre;
      _referencia.text  = f.referencia ?? '';
      _sede.text        = f.sede ?? '';
      _descripcion.text = f.descripcion ?? '';
      _dia.text         = f.diaVencimiento.toString();
      _monto.text       = f.montoEstimado > 0 ? f.montoEstimado.toStringAsFixed(0) : '';
      _correos.text     = f.correos ?? '';
      _aviso.text       = f.diasAviso.toString();
      _categoria        = f.categoria;
    }
  }

  @override
  void dispose() {
    for (final c in [_nombre,_referencia,_sede,_descripcion,_dia,_monto,_correos,_aviso]) c.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final prov = context.watch<PagosProvider>();
    final esEdicion = widget.factura != null;

    return Scaffold(
      backgroundColor: AppColors.bg,
      appBar: AppBar(
        title: Text(esEdicion ? 'Editar Factura' : 'Nueva Factura'),
        actions: [
          TextButton(
            onPressed: _guardando ? null : () => _guardar(prov),
            child: _guardando
                ? const SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2))
                : const Text('Guardar', style: TextStyle(color: AppColors.blue, fontWeight: FontWeight.w700)),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          IosSection(label: 'Identificación', children: [
            IosField(
              icon: Icons.receipt_long, iconColor: AppColors.blue,
              iconBg: const Color(0x1F007AFF), label: 'Nombre *',
              child: IosTextInput(controller: _nombre, placeholder: 'Movistar, Tigo, Luz…'),
            ),
            IosField(
              icon: Icons.label_outline, iconColor: const Color(0xFF5856D6),
              iconBg: const Color(0x1F5856D6), label: 'Categoría',
              child: Row(children: [
                Expanded(
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      value: _categoria,
                      isExpanded: true,
                      hint: const Text('Sin categoría', style: TextStyle(color: AppColors.gray2, fontSize: 14)),
                      style: const TextStyle(fontSize: 14, color: AppColors.label),
                      items: [
                        const DropdownMenuItem(value: null, child: Text('Sin categoría')),
                        ...prov.categorias.map((c) => DropdownMenuItem(value: c, child: Text(c))),
                      ],
                      onChanged: (v) => setState(() => _categoria = v),
                    ),
                  ),
                ),
                GestureDetector(
                  onTap: () => _nuevaCategoria(context, prov),
                  child: Container(
                    width: 26, height: 26,
                    decoration: BoxDecoration(color: const Color(0x1F007AFF), borderRadius: BorderRadius.circular(6)),
                    child: const Icon(Icons.add, color: AppColors.blue, size: 14),
                  ),
                ),
              ]),
            ),
            IosField(
              icon: Icons.location_on_outlined, iconColor: AppColors.green,
              iconBg: const Color(0x1F34C759), label: 'Sede / Ubicación',
              child: IosTextInput(controller: _sede, placeholder: 'Sede Norte, Oficina…'),
            ),
          ]),

          IosSection(label: 'Referencia', children: [
            IosField(
              icon: Icons.tag, iconColor: AppColors.orange,
              iconBg: const Color(0x1FFF9500), label: 'N° Línea / Contrato / Referencia',
              child: IosTextInput(controller: _referencia, placeholder: '573001234567'),
            ),
            IosField(
              icon: Icons.notes, iconColor: AppColors.gray,
              iconBg: const Color(0x1F8E8E93), label: 'Descripción',
              child: IosTextInput(controller: _descripcion, placeholder: 'Observaciones…', maxLines: 2),
            ),
          ]),

          IosSection(label: 'Datos de Pago', children: [
            IosField(
              icon: Icons.calendar_today, iconColor: AppColors.red,
              iconBg: const Color(0x1FFF3B30), label: 'Día de vencimiento (1–31) *',
              child: IosTextInput(controller: _dia, placeholder: '5', keyboardType: TextInputType.number),
            ),
            IosField(
              icon: Icons.attach_money, iconColor: AppColors.green,
              iconBg: const Color(0x1F34C759), label: 'Monto estimado',
              child: IosTextInput(controller: _monto, placeholder: '0', keyboardType: TextInputType.number),
            ),
          ]),

          IosSection(label: 'Recordatorio', children: [
            IosField(
              icon: Icons.email_outlined, iconColor: AppColors.orange,
              iconBg: const Color(0x1FFF9500), label: 'Correos (separados por coma)',
              child: IosTextInput(controller: _correos, placeholder: 'a@mail.com, b@mail.com', keyboardType: TextInputType.emailAddress),
            ),
            IosField(
              icon: Icons.notifications_outlined, iconColor: const Color(0xFF5856D6),
              iconBg: const Color(0x1F5856D6), label: 'Avisar con (días de anticipación)',
              child: IosTextInput(controller: _aviso, placeholder: '3', keyboardType: TextInputType.number),
            ),
          ]),

          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Future<void> _guardar(PagosProvider prov) async {
    final nombre = _nombre.text.trim();
    final dia = int.tryParse(_dia.text.trim());
    if (nombre.isEmpty || dia == null || dia < 1 || dia > 31) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Nombre y día de vencimiento son obligatorios.')));
      return;
    }
    setState(() => _guardando = true);
    final factura = Factura(
      id: widget.factura?.id,
      nombre: nombre,
      categoria: _categoria,
      descripcion: _descripcion.text.trim().isEmpty ? null : _descripcion.text.trim(),
      referencia: _referencia.text.trim().isEmpty ? null : _referencia.text.trim(),
      sede: _sede.text.trim().isEmpty ? null : _sede.text.trim(),
      diaVencimiento: dia,
      montoEstimado: double.tryParse(_monto.text.trim()) ?? 0,
      correos: _correos.text.trim().isEmpty ? null : _correos.text.trim(),
      diasAviso: int.tryParse(_aviso.text.trim()) ?? 3,
    );
    await prov.guardarFactura(factura);
    if (mounted) { setState(() => _guardando = false); Navigator.pop(context); }
  }

  void _nuevaCategoria(BuildContext ctx, PagosProvider prov) {
    final ctrl = TextEditingController();
    showDialog(
      context: ctx,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        title: const Text('Nueva categoría'),
        content: TextField(controller: ctrl, autofocus: true,
          decoration: const InputDecoration(hintText: 'Nombre de la categoría')),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancelar')),
          TextButton(
            onPressed: () async {
              final nombre = ctrl.text.trim();
              if (nombre.isEmpty) return;
              await prov.agregarCategoria(nombre);
              setState(() => _categoria = nombre);
              if (ctx.mounted) Navigator.pop(ctx);
            },
            child: const Text('Agregar', style: TextStyle(color: AppColors.blue, fontWeight: FontWeight.w700)),
          ),
        ],
      ),
    );
  }
}
