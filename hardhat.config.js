require("@nomiclabs/hardhat-waffle");
require('hardhat-abi-exporter');

module.exports = {
  defaultNetwork: 'mumbai',
  networks: {
    matic: {
      url: `https://rpc-mainnet.maticvigil.com/v1/${process.env.MATICVIGIL_API_KEY}`,
      chainId: 137,
      accounts: [process.env.ACCOUNT_KEY],
    },
    mumbai: {
      url: `https://rpc-mumbai.maticvigil.com/v1/${process.env.MATICVIGIL_API_KEY}`,
      chainId: 80001,
      accounts: [process.env.ACCOUNT_KEY],
    }
  },
  etherscan: {
    apiKey: process.env.ETHERSCAN_API_KEY
  },
  abiExporter: {
    path: 'solidity/abis',
    clear: true,
    flat: true
  },
  solidity: {
    version: "0.7.6",
    settings: {
      optimizer: {
        enabled: true,
        runs: 200
      }
    }
  },
  paths: {
    sources: "solidity/contracts",
    tests: "tests",
    cache: "solidity/cache",
    artifacts: "solidity/artifacts"
  },
  mocha: {
    timeout: 20000
  }
};
