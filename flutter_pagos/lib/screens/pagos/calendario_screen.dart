import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../models/factura.dart';
import '../../models/registro_pago.dart';
import '../../providers/pagos_provider.dart';
import '../../theme/app_theme.dart';
import 'nueva_factura_screen.dart';

class CalendarioScreen extends StatefulWidget {
  const CalendarioScreen({super.key});
  @override
  State<CalendarioScreen> createState() => _CalendarioScreenState();
}

class _CalendarioScreenState extends State<CalendarioScreen> {
  static const _meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) =>
        context.read<PagosProvider>().cargar());
  }

  @override
  Widget build(BuildContext context) {
    final prov = context.watch<PagosProvider>();
    final hoy  = DateTime.now();

    return Scaffold(
      backgroundColor: AppColors.bg,
      appBar: AppBar(
        title: const Text('Agenda de Pagos'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add_circle_outline),
            onPressed: () => _abrirFormFactura(context),
          ),
        ],
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(44),
          child: _buildYearNav(prov, hoy),
        ),
      ),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : prov.facturas.isEmpty
              ? _buildEmpty(context)
              : _buildCalendario(prov, hoy),
    );
  }

  Widget _buildYearNav(PagosProvider prov, DateTime hoy) {
    return Container(
      color: AppColors.card,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Row(
        children: [
          _YearBtn(Icons.chevron_left, () => prov.cambiarAnio(prov.anio - 1)),
          const SizedBox(width: 12),
          Text('${prov.anio}', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w700, color: AppColors.label)),
          const SizedBox(width: 12),
          _YearBtn(Icons.chevron_right, () => prov.cambiarAnio(prov.anio + 1)),
          const Spacer(),
          if (prov.totalVencidos > 0)
            _Chip('${prov.totalVencidos} vencido(s)', AppColors.red),
          if (prov.totalProximos > 0) ...[
            const SizedBox(width: 6),
            _Chip('${prov.totalProximos} próximo(s)', AppColors.orange),
          ],
        ],
      ),
    );
  }

  Widget _buildCalendario(PagosProvider prov, DateTime hoy) {
    final grupos = <String?, List<Factura>>{};
    for (final f in prov.facturas) {
      grupos.putIfAbsent(f.categoria, () => []).add(f);
    }

    return SingleChildScrollView(
      scrollDirection: Axis.vertical,
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: IntrinsicWidth(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildHeader(hoy, prov.anio),
              ...grupos.entries.expand((e) => [
                if (e.key != null && e.key!.isNotEmpty) _buildCatRow(e.key!),
                ...e.value.map((f) => _buildFacturaRow(f, prov, hoy)),
              ]),
              const SizedBox(height: 80),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(DateTime hoy, int anio) {
    return Container(
      color: AppColors.card,
      child: Row(
        children: [
          SizedBox(
            width: 180,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              child: Text('Factura', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: AppColors.gray, letterSpacing: .5)),
            ),
          ),
          ..._meses.asMap().entries.map((e) {
            final nm = e.key + 1;
            final esActual = nm == hoy.month && anio == hoy.year;
            return Container(
              width: 54,
              alignment: Alignment.center,
              padding: const EdgeInsets.symmetric(vertical: 10),
              decoration: BoxDecoration(
                color: esActual ? AppColors.blue : null,
                border: const Border(bottom: BorderSide(color: AppColors.gray3, width: .5)),
              ),
              child: Text(e.value,
                style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700,
                  color: esActual ? Colors.white : AppColors.gray, letterSpacing: .4)),
            );
          }),
          const SizedBox(width: 72),
        ],
      ),
    );
  }

  Widget _buildCatRow(String cat) {
    return Container(
      color: AppColors.bg,
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 5),
      child: Text(cat.toUpperCase(),
        style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: AppColors.gray, letterSpacing: .8)),
    );
  }

  Widget _buildFacturaRow(Factura f, PagosProvider prov, DateTime hoy) {
    return Container(
      decoration: const BoxDecoration(
        color: AppColors.card,
        border: Border(bottom: BorderSide(color: AppColors.gray3, width: .5)),
      ),
      child: Row(
        children: [
          SizedBox(
            width: 180,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(f.nombre, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: AppColors.label)),
                  if (f.sede != null && f.sede!.isNotEmpty)
                    Row(children: [
                      const Icon(Icons.location_on, size: 10, color: AppColors.gray),
                      const SizedBox(width: 2),
                      Text(f.sede!, style: const TextStyle(fontSize: 10, color: AppColors.gray)),
                    ]),
                  if (f.referencia != null && f.referencia!.isNotEmpty)
                    Text('# ${f.referencia}', style: const TextStyle(fontSize: 10, color: AppColors.blue)),
                  Text('Día ${f.diaVencimiento}${f.montoEstimado > 0 ? " · \$${NumberFormat('#,###').format(f.montoEstimado)}" : ""}',
                    style: const TextStyle(fontSize: 10, color: AppColors.gray)),
                  if (f.descripcion != null && f.descripcion!.isNotEmpty)
                    Text(
                      f.descripcion!.length > 40 ? '${f.descripcion!.substring(0, 38)}…' : f.descripcion!,
                      style: const TextStyle(fontSize: 10, color: AppColors.gray2, fontStyle: FontStyle.italic),
                    ),
                ],
              ),
            ),
          ),
          ..._meses.asMap().entries.map((e) {
            final nm = e.key + 1;
            final ec = prov.estadoCelda(f, nm);
            final esProx = prov.esProximo(f, nm);
            final reg = prov.getRegistro(f.id!, nm);
            return _CeldaMes(
              dia: f.diaVencimiento,
              estado: ec,
              esProximo: esProx,
              onTap: () => _onCeldaTap(context, f, reg, nm, prov),
            );
          }),
          SizedBox(
            width: 72,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _IconBtn(Icons.edit_outlined, AppColors.orange, () => _abrirFormFactura(context, factura: f)),
                const SizedBox(width: 4),
                _IconBtn(Icons.delete_outline, AppColors.red, () => _confirmarEliminar(context, f, prov)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _onCeldaTap(BuildContext ctx, Factura f, RegistroPago? reg, int mes, PagosProvider prov) {
    if (reg == null) return;
    if (reg.estado == EstadoPago.pagado) {
      _confirmarRevertir(ctx, f, reg, mes, prov);
    } else {
      _mostrarFormPago(ctx, f, reg, mes, prov);
    }
  }

  void _mostrarFormPago(BuildContext ctx, Factura f, RegistroPago reg, int mes, PagosProvider prov) {
    final montoCtrl = TextEditingController(
      text: f.montoEstimado > 0 ? f.montoEstimado.toStringAsFixed(0) : '');
    final notasCtrl = TextEditingController();
    DateTime fechaPago = DateTime.now();

    showModalBottomSheet(
      context: ctx,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => StatefulBuilder(builder: (ctx2, setState2) {
        return Container(
          padding: EdgeInsets.only(bottom: MediaQuery.of(ctx2).viewInsets.bottom),
          decoration: const BoxDecoration(
            color: AppColors.card,
            borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
          ),
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(child: Container(width: 36, height: 4, decoration: BoxDecoration(color: AppColors.gray3, borderRadius: BorderRadius.circular(2)))),
                const SizedBox(height: 16),
                Text('Registrar pago', style: Theme.of(ctx2).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700)),
                Text('${f.nombre} — ${_meses[mes - 1]} ${prov.anio}', style: const TextStyle(color: AppColors.gray, fontSize: 13)),
                const SizedBox(height: 16),
                GestureDetector(
                  onTap: () async {
                    final d = await showDatePicker(context: ctx2,
                      initialDate: fechaPago, firstDate: DateTime(2020), lastDate: DateTime.now());
                    if (d != null) setState2(() => fechaPago = d);
                  },
                  child: Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(color: AppColors.bg, borderRadius: BorderRadius.circular(10)),
                    child: Row(children: [
                      const Icon(Icons.calendar_today, size: 16, color: AppColors.blue),
                      const SizedBox(width: 8),
                      Text(DateFormat('dd/MM/yyyy').format(fechaPago), style: const TextStyle(fontWeight: FontWeight.w600, color: AppColors.blue)),
                    ]),
                  ),
                ),
                const SizedBox(height: 10),
                TextField(controller: montoCtrl,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Monto pagado', prefixText: '\$ ')),
                const SizedBox(height: 10),
                TextField(controller: notasCtrl, maxLines: 2,
                  decoration: const InputDecoration(labelText: 'Notas (opcional)')),
                const SizedBox(height: 20),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(backgroundColor: AppColors.green),
                    onPressed: () async {
                      await prov.marcarPagado(reg,
                        fechaPago: fechaPago,
                        monto: double.tryParse(montoCtrl.text),
                        notas: notasCtrl.text.isNotEmpty ? notasCtrl.text : null,
                      );
                      if (ctx2.mounted) Navigator.pop(ctx2);
                    },
                    child: const Text('✓ Confirmar pago'),
                  ),
                ),
              ],
            ),
          ),
        );
      }),
    );
  }

  void _confirmarRevertir(BuildContext ctx, Factura f, RegistroPago reg, int mes, PagosProvider prov) {
    showDialog(
      context: ctx,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        title: const Text('Revertir pago'),
        content: Text('¿Marcar ${f.nombre} (${_meses[mes - 1]}) como pendiente?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancelar')),
          TextButton(
            onPressed: () async {
              await prov.revertirPago(reg);
              if (ctx.mounted) Navigator.pop(ctx);
            },
            child: const Text('Revertir', style: TextStyle(color: AppColors.orange)),
          ),
        ],
      ),
    );
  }

  void _confirmarEliminar(BuildContext ctx, Factura f, PagosProvider prov) {
    showDialog(
      context: ctx,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        title: const Text('Desactivar factura'),
        content: Text('¿Desactivar "${f.nombre}"? Los registros se conservan.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancelar')),
          TextButton(
            onPressed: () async {
              await prov.eliminarFactura(f.id!);
              if (ctx.mounted) Navigator.pop(ctx);
            },
            child: const Text('Desactivar', style: TextStyle(color: AppColors.red)),
          ),
        ],
      ),
    );
  }

  void _abrirFormFactura(BuildContext ctx, {Factura? factura}) {
    Navigator.push(ctx, MaterialPageRoute(
      builder: (_) => NuevaFacturaScreen(factura: factura),
    )).then((_) => context.read<PagosProvider>().cargar());
  }

  Widget _buildEmpty(BuildContext ctx) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Icon(Icons.calendar_month, size: 64, color: AppColors.gray3),
          const SizedBox(height: 12),
          const Text('Sin facturas', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700, color: AppColors.label)),
          const Text('Agrega tu primera factura', style: TextStyle(color: AppColors.gray)),
          const SizedBox(height: 20),
          ElevatedButton.icon(
            onPressed: () => _abrirFormFactura(ctx),
            icon: const Icon(Icons.add),
            label: const Text('Nueva Factura'),
          ),
        ],
      ),
    );
  }
}

// ── Widgets auxiliares ────────────────────────────────────────
class _YearBtn extends StatelessWidget {
  final IconData icon;
  final VoidCallback onTap;
  const _YearBtn(this.icon, this.onTap);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 32, height: 32,
        decoration: BoxDecoration(color: AppColors.bg, borderRadius: BorderRadius.circular(16)),
        child: Icon(icon, color: AppColors.blue, size: 18),
      ),
    );
  }
}

class _Chip extends StatelessWidget {
  final String label;
  final Color color;
  const _Chip(this.label, this.color);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(color: color.withOpacity(.12), borderRadius: BorderRadius.circular(20)),
      child: Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: color)),
    );
  }
}

class _CeldaMes extends StatelessWidget {
  final int dia;
  final EstadoPago estado;
  final bool esProximo;
  final VoidCallback onTap;
  const _CeldaMes({required this.dia, required this.estado, required this.esProximo, required this.onTap});

  @override
  Widget build(BuildContext context) {
    Color bg, border, fg;
    IconData? ico;
    if (estado == EstadoPago.pagado) {
      bg = const Color(0xFFE8FAF0); border = AppColors.green; fg = const Color(0xFF1A7A35); ico = Icons.check;
    } else if (estado == EstadoPago.vencido) {
      bg = const Color(0xFFFFF0EF); border = AppColors.red; fg = AppColors.red; ico = Icons.priority_high;
    } else if (esProximo) {
      bg = const Color(0xFFFFF8E6); border = AppColors.orange; fg = AppColors.orange; ico = Icons.notifications_outlined;
    } else {
      bg = AppColors.bg; border = AppColors.gray3; fg = AppColors.gray; ico = null;
    }

    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 54,
        height: 56,
        margin: const EdgeInsets.all(2),
        decoration: BoxDecoration(
          color: bg,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: border, width: 1.5),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('$dia', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: fg, height: 1)),
            if (ico != null) Icon(ico, size: 10, color: fg),
          ],
        ),
      ),
    );
  }
}

class _IconBtn extends StatelessWidget {
  final IconData icon;
  final Color color;
  final VoidCallback onTap;
  const _IconBtn(this.icon, this.color, this.onTap);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 30, height: 30,
        decoration: BoxDecoration(color: color.withOpacity(.12), borderRadius: BorderRadius.circular(8)),
        child: Icon(icon, size: 14, color: color),
      ),
    );
  }
}
