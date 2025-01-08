(function ($, Drupal) {
  Drupal.behaviors.taskGraph = {
    attach: function (context, settings) {
      // Access data passed from PHP.
      const graphData = settings.taskGraph || {};

      // Render the graph.
      const graphContainer = $('#graph', context);
      if (graphContainer.length && Object.keys(graphData).length > 0) {
        graphContainer.empty();
        Object.keys(graphData).forEach((key) => {
          const value = graphData[key];
          graphContainer.append(
            `<div class="bar" style="height: ${value}px;">${value}</div>`
          );
        });
      }
    },
  };
})(jQuery, Drupal);
