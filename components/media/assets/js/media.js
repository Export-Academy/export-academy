/**
 * @typedef HandlerConfiguration
 * @property {string[]} types
 */



class Handler {
  static types = {
    VideoHandler: VideoHandler,
    ImageHandler: ImageHandler,
    ApplicationHandler: ApplicationHandler
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
      context.detailContainer.hide();
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
  }


  configure(handler) {
    const { mime, path, create_date, update_date, created_by, updated_by, name } = handler.details;
    $("#filename").html(name);
    $("#directory").html(path);
    $("#mime-type").html(mime);
    $("#created-user").html(created_by);
    $("#created-date").html(create_date);
    $("#updated-user").html(updated_by);
    $("#updated-date").html(update_date);
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

    this.configure(handler);


    handler.container.show();
    this.detailContainer.show();
  }


  get container() {
    return $(".media-container");
  }


  get detailContainer() {
    return $("#media-details-container-handle");
  }
}

Handler.init();



