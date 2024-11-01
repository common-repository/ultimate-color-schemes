const { Parcel } = require('@parcel/core');
const glob = require('glob');
const rtlcss = require('rtlcss');

// Dynamically find all SASS files in the 'assets/schemes' directory
const entryFiles = glob.sync('assets/schemes/**/admin.scss')
  .concat(glob.sync('assets/schemes/**/frontend.scss'))
  .concat(glob.sync('assets/admin/admin.js'))
  .concat(glob.sync('assets/admin/admin.scss'))
  .concat(glob.sync('assets/admin/settings-page/scripts.js'))
  .concat(glob.sync('assets/admin/settings-page/styles.scss'))
  .concat(glob.sync('assets/editor.scss'));

const rtlFiles = glob.sync('assets/schemes/**/admin.scss')
  .concat(glob.sync('assets/schemes/**/frontend.scss'));

// Parcel options
const options = {
  entries: entryFiles,
  defaultConfig: '@parcel/config-default',
  distDir: 'dist',
  defaultTargetOptions: {
    sourceMaps: false, // Adjust as needed
    shouldOptimize: true, // Enable optimization
    shouldMinify: true, // Enable minification
  }
};

// RTL options
const rtlOptions = {
  entries: rtlFiles,
  defaultConfig: '@parcel/config-default',
  distDir: 'dist/schemes/rtl',
  defaultTargetOptions: {
    sourceMaps: false, // Adjust as needed
    shouldOptimize: true, // Enable optimization
    shouldMinify: true, // Enable minification
  },
  transformers: {
    css: {
      plugins: [
        {
          postcss: {
            plugins: [
              rtlcss,
            ],
          },
        },
      ],
    },
  },
  targets: {
    default: {
      distDir: 'dist/schemes-rtl', // Output directory
    },
  },
};

async function build() {
  try {
    const parcel = new Parcel(options);
    await parcel.run();
    console.log('Build completed successfully.');
  } catch (error) {
    console.error('Build failed:', error);
  }
}

async function buildRtl() {
  try {
    const parcel = new Parcel(rtlOptions);
    await parcel.run();
    console.log('Build completed successfully.');
  } catch (error) {
    console.error('Build failed:', error);
  }
}

async function watch() {
  try {
    const parcel = new Parcel({ ...options, watch: true });
    await parcel.watch();
    console.log('Watching for changes...');
  } catch (error) {
    console.error('Error during watch:', error);
  }
}

// Determine whether to build or watch based on command line arguments
const args = process.argv.slice(2);
if (args.includes('--watch')) {
  watch();
} else if (args.includes('--rtl')) {
  buildRtl();
} else {
  build();
}