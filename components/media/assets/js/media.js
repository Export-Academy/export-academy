/**
 * @typedef HandlerConfiguration
 * @property {string[]} types
 */



class Handler {
  static types = {
    VideoHandler: VideoHandler,
    ImageHandler: ImageHandler
  };

  /**
   * 
   * @param {HandlerConfiguration} config 
   */
  static init(config = {}) {
    const { } = config;
    const handler = new Handler();
    handler.initialize();
    return handler;
  }

  initialize() {
    const context = this;
    $(".view-asset-button").on('click', function (e) {
      context.container.children().hide();
      context.handleSelectMedia(e, context);
    });
  }


  async handleSelectMedia(e, context) {
    const target = $(e.currentTarget);

    const key = target.data("key");
    const type = target.data("type");


    $("[id^=thumbnail]").removeClass('active');
    $(`#thumbnail-${key}`).addClass('active');

    if (Handler.types.hasOwnProperty(type)) {
      await context.handleMedia(key, type);;
    }

    console.log(target, key, type);
  }

  /**
   * 
   * @param {string} key 
   * @param {string} type 
   */
  async handleMedia(key, type) {
    let handler = Handler.types[type].getHandler(key);
    if (handler === null)
      handler = await Handler.types[type].init({ id: key });

    handler.initialize();
    handler.container.show();
    console.log("Initialized");
    console.log(handler);
  }


  get container() {
    return $(".media-container");
  }
}

Handler.init();



