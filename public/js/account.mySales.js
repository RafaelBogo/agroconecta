document.addEventListener('DOMContentLoaded', () => {
  // Pega o JSON renderizado pelo Blade
  const dataEl = document.getElementById('sales-data');
  if (!dataEl) return;

  let D = {};
  try {
    D = JSON.parse(dataEl.textContent || '{}');
  } catch (e) {
    console.error('JSON de vendas inválido:', e);
    return;
  }

  // Paleta
  const green  = 'rgba(25,135,84,0.9)',  greenL = 'rgba(25,135,84,0.25)';
  const blue   = 'rgba(13,110,253,0.9)', blueL  = 'rgba(13,110,253,0.2)';
  const colors = ['#198754','#0d6efd','#ffc107','#dc3545','#6f42c1','#20c997','#fd7e14','#6c757d'];

  // Helpers
  const moneyBR = v => Number(v).toLocaleString('pt-BR', { style:'currency', currency:'BRL' });

  // ----- Vendas x Pedidos por dia
  const cvp = document.getElementById('chartVendasPedidos');
  if (cvp) new Chart(cvp, {
    type: 'bar',
    data: {
      labels: D.labelsDias || [],
      datasets: [
        { type:'line', label:'Vendas (R$)', data:D.serieVendas || [], yAxisID:'y',
          borderColor:green, backgroundColor:greenL, borderWidth:2, tension:0.3, pointRadius:0 },
        { type:'bar', label:'Pedidos', data:D.seriePedidos || [], yAxisID:'y1',
          backgroundColor:blueL, borderColor:blue, borderWidth:1, borderRadius:6, maxBarThickness:24 }
      ]
    },
    options: {
      responsive:true, maintainAspectRatio:false, interaction:{ mode:'index', intersect:false },
      scales:{
        y:{ position:'left', beginAtZero:true,
            ticks:{ callback:v => moneyBR(v) }, grid:{ drawOnChartArea:false } },
        y1:{ position:'right', beginAtZero:true, grid:{ drawOnChartArea:false } },
        x:{ grid:{ display:false } }
      },
      plugins:{
        legend:{ labels:{ usePointStyle:true }},
        tooltip:{ callbacks:{
          label:(ctx)=> ctx.dataset.label.includes('Vendas')
            ? `${ctx.dataset.label}: ${moneyBR(ctx.raw)}`
            : `${ctx.dataset.label}: ${ctx.raw}`
        }}
      }
    }
  });

  // ----- Ticket médio por dia
  const caov = document.getElementById('chartAovDia');
  if (caov) new Chart(caov, {
    type:'line',
    data:{ labels:D.labelsDias || [], datasets:[{
      label:'Ticket médio (R$)', data:D.serieAov || [],
      borderColor:blue, backgroundColor:blueL, borderWidth:2, tension:0.3, pointRadius:2
    }]},
    options:{
      responsive:true, maintainAspectRatio:false,
      scales:{
        y:{ beginAtZero:true, ticks:{ callback:v=> moneyBR(v) }},
        x:{ grid:{ display:false } }
      }
    }
  });

  // ----- Pedidos por horário
  const ch = document.getElementById('chartPedidosHora');
  if (ch) new Chart(ch, {
    type:'bar',
    data:{ labels:D.labelsHoras || [], datasets:[{
      label:'Pedidos', data:D.seriePedidosHora || [],
      backgroundColor:greenL, borderColor:green, borderWidth:1.5, borderRadius:6
    }]},
    options:{ responsive:true, maintainAspectRatio:false,
      scales:{ y:{ beginAtZero:true }, x:{ grid:{ display:false } } },
      plugins:{ legend:{ display:false } }
    }
  });

  // ----- Top 5 produtos por unidades
  const ctop = document.getElementById('chartTopProdutosQty');
  if (ctop) new Chart(ctop, {
    type:'bar',
    data:{ labels:D.labelsProdutosQty || [], datasets:[{
      label:'Unidades', data:D.serieUnidadesProdutos || [],
      backgroundColor:blueL, borderColor:blue, borderWidth:1.5, borderRadius:8
    }]},
    options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false,
      scales:{ x:{ beginAtZero:true, grid:{ drawBorder:false } }, y:{ grid:{ display:false } } },
      plugins:{ legend:{ display:false } }
    }
  });

  // ----- Rosca de status
  const cstatus = document.getElementById('chartStatus');
  if (cstatus) new Chart(cstatus, {
    type:'doughnut',
    data:{
      labels: D.labelsStatus || [],
      datasets:[{
        data: D.serieStatus || [],
        backgroundColor: (D.labelsStatus || []).map((_,i)=>colors[i % colors.length]),
        borderColor:'#fff', borderWidth:1
      }]
    },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, cutout:'60%' }
  });
});
