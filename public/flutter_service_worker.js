'use strict';
const MANIFEST = 'flutter-app-manifest';
const TEMP = 'flutter-temp-cache';
const CACHE_NAME = 'flutter-app-cache';

const RESOURCES = {"assets/AssetManifest.bin": "c4eb354e94e4214a2f2320dfacd34d96",
"assets/AssetManifest.bin.json": "ad37b880e0230a46919def33b8d061bb",
"assets/AssetManifest.json": "593088970d9128225efd47ced4f3a48a",
"assets/assets/fonts/Roboto-Regular.ttf": "303c6d9e16168364d3bc5b7f766cfff4",
"assets/assets/images/banner1.png": "0f67b7db33a66f0ceba17defee8efa6c",
"assets/assets/images/banner2.png": "bde6d2b9c8bfabe86ee1f4076f31bbdf",
"assets/assets/images/cart_text.svg": "5407979494838f6ee9bdb086a762278a",
"assets/assets/images/delete.svg": "7f7602f0b81d289fb81533c6eb63555e",
"assets/assets/images/empty_cart.svg": "9456a778ebf34117a8b520ae3d66aeb0",
"assets/assets/images/fav_empty.svg": "7b34b11db24cecb7bf29ca8b5fc7c6d6",
"assets/assets/images/icons/add.png": "a8e51143cd2564e5ec861aaa23382477",
"assets/assets/images/icons/add.svg": "70d163a508aa2fb2167c06a804e03c0f",
"assets/assets/images/icons/cart.svg": "d49a3e9d888e67e72319df7c76ddb22d",
"assets/assets/images/icons/category.svg": "3020abd71d4ed056342214defe1001b9",
"assets/assets/images/icons/delete.svg": "1bf9785c97dce4f4d69aa57952a70c13",
"assets/assets/images/icons/discount.svg": "e1b0ecdbeb274840355614709faab7a3",
"assets/assets/images/icons/drawer.svg": "7ea7aa7d77461a49036768a3c9388bfb",
"assets/assets/images/icons/edit.svg": "f9b4f8affc02a8935e97a334d9419235",
"assets/assets/images/icons/home.svg": "c3f3ad0d6c63711862fb11280aa036a1",
"assets/assets/images/icons/image.svg": "faef79bb3eb5757aaa81ecda6b374bcb",
"assets/assets/images/icons/img.svg": "39e02099a7e1e30fc80c5cbc520fd1b7",
"assets/assets/images/icons/logo.svg": "636952624f679d6a467a4afe56913a44",
"assets/assets/images/icons/md_edit.svg": "ff80bad13a82517b395a0e76a1d2ee32",
"assets/assets/images/icons/new_cart.svg": "64c9bb76726629aaade8d7d1009612ab",
"assets/assets/images/icons/order.svg": "ee288e68d45bf30e221afdf73e3087a9",
"assets/assets/images/icons/product.svg": "7e1db31531b031f627c22baa1de69470",
"assets/assets/images/icons/profile.svg": "f272c0984e981834623f032d716de567",
"assets/assets/images/icons/search.svg": "eb065d75e0a9b4024407d7aeb8147adb",
"assets/assets/images/icons/see_all.svg": "df2d69286c442ee155f9cee3f828a05f",
"assets/assets/images/icons/settings.svg": "7f1c53ca2c7d2cb31e753cdc1fe05403",
"assets/assets/images/icons/share.svg": "4690175dc9a4177028ec1df80c69e58e",
"assets/assets/images/icons/success.svg": "ba69c1e1cc27a218df8122f8d55683b4",
"assets/assets/images/icons/trash.svg": "23e4a712a7d821040415f42c195a04eb",
"assets/assets/images/icons/view_details.svg": "053dcfe856a547ca1f364e8db807cc1a",
"assets/assets/images/icons/view_product.svg": "48f7ddef12b270bc12154663d564eff8",
"assets/assets/images/icons/view_under.svg": "b229c80f1d1a3ffba43f44e305ebebf7",
"assets/assets/images/icons/wishlist.svg": "d145075448e3e212c91a3aa3c459ee06",
"assets/assets/images/lit_store%2520(2).png": "b92acb5efffee9f6ea88f053ac4fd18c",
"assets/assets/images/lit_store.png": "eb351a9a11c5c4868ea75a6d084dcbc8",
"assets/assets/images/logo.png": "99b66334d8f684721a5a145f8a62f98f",
"assets/assets/images/logo.svg": "636952624f679d6a467a4afe56913a44",
"assets/assets/images/onboard1.png": "9a9bd7c29ce030b19224754f4d21d9ac",
"assets/assets/images/onboard2.png": "fc2bb8716fe0432dea29656222968d9f",
"assets/assets/images/onboard3.png": "3d7e03531615ddeb5b837ccb9d371c7f",
"assets/assets/images/Progress.svg": "6608b92847c9dfeb9ae2df44cb4bd208",
"assets/assets/images/success.png": "f368447819d9cfef68de8bb8fd845d2b",
"assets/assets/images/success.svg": "f5daf4161399a4190c6d5d791241afca",
"assets/FontManifest.json": "dc3d03800ccca4601324923c0b1d6d57",
"assets/fonts/MaterialIcons-Regular.otf": "1640f8dac8fef4e93a4a977d023e6439",
"assets/NOTICES": "07c196184b0e9b7aa4dfb1a1f3ba59f5",
"assets/packages/cupertino_icons/assets/CupertinoIcons.ttf": "33b7d9392238c04c131b6ce224e13711",
"assets/shaders/ink_sparkle.frag": "ecc85a2e95f5e9f53123dcaf8cb9b6ce",
"canvaskit/canvaskit.js": "86e461cf471c1640fd2b461ece4589df",
"canvaskit/canvaskit.js.symbols": "68eb703b9a609baef8ee0e413b442f33",
"canvaskit/canvaskit.wasm": "efeeba7dcc952dae57870d4df3111fad",
"canvaskit/chromium/canvaskit.js": "34beda9f39eb7d992d46125ca868dc61",
"canvaskit/chromium/canvaskit.js.symbols": "5a23598a2a8efd18ec3b60de5d28af8f",
"canvaskit/chromium/canvaskit.wasm": "64a386c87532ae52ae041d18a32a3635",
"canvaskit/skwasm.js": "f2ad9363618c5f62e813740099a80e63",
"canvaskit/skwasm.js.symbols": "80806576fa1056b43dd6d0b445b4b6f7",
"canvaskit/skwasm.wasm": "f0dfd99007f989368db17c9abeed5a49",
"canvaskit/skwasm_st.js": "d1326ceef381ad382ab492ba5d96f04d",
"canvaskit/skwasm_st.js.symbols": "c7e7aac7cd8b612defd62b43e3050bdd",
"canvaskit/skwasm_st.wasm": "56c3973560dfcbf28ce47cebe40f3206",
"favicon.png": "5dcef449791fa27946b3d35ad8803796",
"flutter.js": "76f08d47ff9f5715220992f993002504",
"flutter_bootstrap.js": "a774c70e53c0d6fd48fddde390adc32c",
"icons/Icon-192.png": "ac9a721a12bbc803b44f645561ecb1e1",
"icons/Icon-512.png": "96e752610906ba2a93c65f8abe1645f1",
"icons/Icon-maskable-192.png": "c457ef57daa1d16f64b27b786ec2ea3c",
"icons/Icon-maskable-512.png": "301a7604d45b3e739efc881eb04896ea",
"index.html": "6ecd10f41278bdedb48eb44b2194ad55",
"/": "6ecd10f41278bdedb48eb44b2194ad55",
"main.dart.js": "76b1a7c705602e98e91bf8aa2e9b8fa4",
"manifest.json": "2951824e396303ee03e0f01cfc9dff88",
"version.json": "dad7aaa12eee1e99b33303898a80ac15"};
// The application shell files that are downloaded before a service worker can
// start.
const CORE = ["main.dart.js",
"index.html",
"flutter_bootstrap.js",
"assets/AssetManifest.bin.json",
"assets/FontManifest.json"];

// During install, the TEMP cache is populated with the application shell files.
self.addEventListener("install", (event) => {
  self.skipWaiting();
  return event.waitUntil(
    caches.open(TEMP).then((cache) => {
      return cache.addAll(
        CORE.map((value) => new Request(value, {'cache': 'reload'})));
    })
  );
});
// During activate, the cache is populated with the temp files downloaded in
// install. If this service worker is upgrading from one with a saved
// MANIFEST, then use this to retain unchanged resource files.
self.addEventListener("activate", function(event) {
  return event.waitUntil(async function() {
    try {
      var contentCache = await caches.open(CACHE_NAME);
      var tempCache = await caches.open(TEMP);
      var manifestCache = await caches.open(MANIFEST);
      var manifest = await manifestCache.match('manifest');
      // When there is no prior manifest, clear the entire cache.
      if (!manifest) {
        await caches.delete(CACHE_NAME);
        contentCache = await caches.open(CACHE_NAME);
        for (var request of await tempCache.keys()) {
          var response = await tempCache.match(request);
          await contentCache.put(request, response);
        }
        await caches.delete(TEMP);
        // Save the manifest to make future upgrades efficient.
        await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
        // Claim client to enable caching on first launch
        self.clients.claim();
        return;
      }
      var oldManifest = await manifest.json();
      var origin = self.location.origin;
      for (var request of await contentCache.keys()) {
        var key = request.url.substring(origin.length + 1);
        if (key == "") {
          key = "/";
        }
        // If a resource from the old manifest is not in the new cache, or if
        // the MD5 sum has changed, delete it. Otherwise the resource is left
        // in the cache and can be reused by the new service worker.
        if (!RESOURCES[key] || RESOURCES[key] != oldManifest[key]) {
          await contentCache.delete(request);
        }
      }
      // Populate the cache with the app shell TEMP files, potentially overwriting
      // cache files preserved above.
      for (var request of await tempCache.keys()) {
        var response = await tempCache.match(request);
        await contentCache.put(request, response);
      }
      await caches.delete(TEMP);
      // Save the manifest to make future upgrades efficient.
      await manifestCache.put('manifest', new Response(JSON.stringify(RESOURCES)));
      // Claim client to enable caching on first launch
      self.clients.claim();
      return;
    } catch (err) {
      // On an unhandled exception the state of the cache cannot be guaranteed.
      console.error('Failed to upgrade service worker: ' + err);
      await caches.delete(CACHE_NAME);
      await caches.delete(TEMP);
      await caches.delete(MANIFEST);
    }
  }());
});
// The fetch handler redirects requests for RESOURCE files to the service
// worker cache.
self.addEventListener("fetch", (event) => {
  if (event.request.method !== 'GET') {
    return;
  }
  var origin = self.location.origin;
  var key = event.request.url.substring(origin.length + 1);
  // Redirect URLs to the index.html
  if (key.indexOf('?v=') != -1) {
    key = key.split('?v=')[0];
  }
  if (event.request.url == origin || event.request.url.startsWith(origin + '/#') || key == '') {
    key = '/';
  }
  // If the URL is not the RESOURCE list then return to signal that the
  // browser should take over.
  if (!RESOURCES[key]) {
    return;
  }
  // If the URL is the index.html, perform an online-first request.
  if (key == '/') {
    return onlineFirst(event);
  }
  event.respondWith(caches.open(CACHE_NAME)
    .then((cache) =>  {
      return cache.match(event.request).then((response) => {
        // Either respond with the cached resource, or perform a fetch and
        // lazily populate the cache only if the resource was successfully fetched.
        return response || fetch(event.request).then((response) => {
          if (response && Boolean(response.ok)) {
            cache.put(event.request, response.clone());
          }
          return response;
        });
      })
    })
  );
});
self.addEventListener('message', (event) => {
  // SkipWaiting can be used to immediately activate a waiting service worker.
  // This will also require a page refresh triggered by the main worker.
  if (event.data === 'skipWaiting') {
    self.skipWaiting();
    return;
  }
  if (event.data === 'downloadOffline') {
    downloadOffline();
    return;
  }
});
// Download offline will check the RESOURCES for all files not in the cache
// and populate them.
async function downloadOffline() {
  var resources = [];
  var contentCache = await caches.open(CACHE_NAME);
  var currentContent = {};
  for (var request of await contentCache.keys()) {
    var key = request.url.substring(origin.length + 1);
    if (key == "") {
      key = "/";
    }
    currentContent[key] = true;
  }
  for (var resourceKey of Object.keys(RESOURCES)) {
    if (!currentContent[resourceKey]) {
      resources.push(resourceKey);
    }
  }
  return contentCache.addAll(resources);
}
// Attempt to download the resource online before falling back to
// the offline cache.
function onlineFirst(event) {
  return event.respondWith(
    fetch(event.request).then((response) => {
      return caches.open(CACHE_NAME).then((cache) => {
        cache.put(event.request, response.clone());
        return response;
      });
    }).catch((error) => {
      return caches.open(CACHE_NAME).then((cache) => {
        return cache.match(event.request).then((response) => {
          if (response != null) {
            return response;
          }
          throw error;
        });
      });
    })
  );
}
